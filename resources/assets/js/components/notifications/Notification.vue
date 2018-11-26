<template>
    <li class="media my-4 p-1" :class="{ 'border-left': !readStatus }">
        <div class="media-body">
            <div class="d-flex justify-content-between align-content-center">
                <h5 class="mt-0 mb-2">{{ notification.data.title }}</h5>

                <div>
                    <a href="#" @click.prevent="markAsRead" v-if="showReadButton">Mark as read</a>
                    <a href="#" @click.prevent="markAndShow" v-if="!show">Show</a>
                </div>
            </div>

            <div class="py-1 mt-3 mb-2" v-if="show">
                {{ notification.data.body }}

                <p class="mt-1" v-if="read">
                    <a href="#" @click.prevent="show = false">Hide</a>
                </p>
            </div>

            <ul class="list-inline">
                <li class="list-inline-item">
                    <timeago :since="notification.created_at" :auto-update="60"></timeago>
                </li>
                <li class="list-inline-item" v-if="notification.data.url">
                    <a :href="notification.data.url">{{ notificationActionLabel }}</a>
                </li>
                <li class="list-inline-item">
                    <a href="#" @click.prevent="destroy">Delete</a>
                </li>
            </ul>
        </div>
    </li>
</template>

<script>
    import axios from 'axios'

    export default {
        name: "notification",
        props: {
            endpoint: {
                required: true,
                type: String
            },
            notification: {
                required: true,
                type: Object
            }
        },
        data() {
            return {
                show: false,
                read: this.notification.read_at
            }
        },
        computed: {
            notificationActionLabel() {
                return this.notification.data.action || 'View'
            },
            showReadButton() {
                if (!this.read && this.show) {
                    return false
                }

                if (!this.read) {
                    return true
                }

                return false
            },
            readStatus() {
                if (!this.read) {
                    return false
                }

                return true
            }
        },
        methods: {
            async markAndShow() {
                this.show = true

                if (!this.read) {
                    this.markAsRead()
                }
            },
            async markAsRead() {
                let response = await axios.put(`${this.endpoint}/${this.notification.id}`)

                this.read = response.data.read_at

                this.$emit('notification:read', this.notification)
            },
            async destroy() {
                let response = await axios.delete(`${this.endpoint}/${this.notification.id}`)

                this.$emit('notification:deleted', this.notification)
            }
        }
    }
</script>

<style scoped>
    .border-left {
        border-left: 3px solid #dee2e6 !important;
    }
</style>