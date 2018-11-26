<template>
    <div id="notifications-wrapper" class="mb-3">
        <h5 v-if="!noHeader">
            Notifications
            <span class="badge badge-primary badge-pill" v-if="meta.unread > 0">{{ meta.unread }}</span>
        </h5>

        <ul class="mt-3 mb-2 list-inline" v-if="meta.total">
            <li class="list-inline-item" v-if="meta.total > 0">
                <a href="#" @click.prevent="loadNotifications(1)">Refresh</a>
            </li>
            <li class="list-inline-item" v-if="meta.unread > 0">
                <a href="#" @click.prevent="markAllAsRead">Mark all as read</a>
            </li>
        </ul>

        <template v-if="notifications.length">
            <ul class="list-unstyled">
                <Notification :endpoint="endpoint"
                              :notification="notification"
                              v-for="notification in notifications"
                              :key="notification.id"
                              v-on:notification:deleted="removeNotification"
                              v-on:notification:read="updateUnreadNotification"/>
            </ul>
        </template>

        <p class="mt-4" v-else>No notifications to display.</p>

        <a href="#"
           class="btn btn-light btn-block shadow-none"
           @click.prevent="loadMore"
           v-if="meta.current_page < meta.last_page">
            Show more
        </a>
    </div>
</template>

<script>
    import Notification from './notification'
    import axios from 'axios'
    import Bus from '../../bus'

    export default {
        name: "notifications",
        props: {
            endpoint: {
                required: true,
                type: String
            },
            noHeader: {
                required: false,
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                notifications: [],
                meta: {}
            }
        },
        components: {
            Notification
        },
        mounted() {
            this.loadNotifications()
        },
        methods: {
            fetchNotifications(page = 1) {
                return axios.get(`${this.endpoint}?page=${page}`)
            },
            async loadNotifications(page = 1) {
                let response = await this.fetchNotifications(page)

                this.notifications= []
                this.notifications = response.data.data

                this.meta = {}
                this.meta = response.data.meta

                Bus.$emit('notifications:loaded', this.meta.unread)
            },
            async fetchMeta() {
                let response = await this.fetchNotifications(this.meta.current_page)

                this.meta = response.data.meta

                Bus.$emit('notifications:loaded', this.meta.unread)
            },
            async reloadCurrent() {
                let response = await this.fetchNotifications(this.meta.current_page)

                this.notifications = response.data.data
                this.meta = response.data.meta

                Bus.$emit('notifications:loaded', this.meta.unread)
            },
            async loadMore() {
                let response = await this.fetchNotifications(this.meta.current_page + 1)

                this.notifications.push(...response.data.data)
                this.meta = response.data.meta

                Bus.$emit('notifications:loaded', this.meta.unread)
            },
            async loadOneAfterDeletion() {
                if (this.notifications.length === 0 && this.meta.last_page < this.meta.current_page) {
                    this.loadNotifications(this.meta.last_page)

                    return
                }

                if (this.meta.current_page >= this.meta.last_page) {
                    return
                }

                let response = await this.fetchNotifications(this.meta.current_page)

                this.notifications.push(response.data.data[response.data.data.length - 1])
                this.meta = response.data.meta

                Bus.$emit('notifications:loaded', this.meta.unread)
            },
            async markAllAsRead() {
                let response = await axios.post(`${this.endpoint}`)

                this.loadNotifications()

                this.scrollToTop()
            },
            async updateUnreadNotification(notification) {
                this.meta.unread--

                Bus.$emit('notification:read', this.meta.unread)
            },
            async removeNotification(notification) {
                this.notifications = this.notifications.filter((notify) => {
                    return notify.id !== notification.id
                })

                this.meta.total--

                if (!notification.read_at) {
                    this.meta.unread--

                    Bus.$emit('notification:removed', this.meta.unread)
                }

                this.loadOneAfterDeletion()
            },
            async prependNotification(notification) {
                this.notifications.unshift(notification)

                await this.fetchMeta()

                if (this.meta.current_page < this.meta.last_page) {
                    this.notifications.pop()
                }

                this.scrollToTop()
            },
            scrollToTop() {
                this.$scrollTo('#notifications-wrapper', 500)
            }
        }
    }
</script>

<style scoped>

</style>