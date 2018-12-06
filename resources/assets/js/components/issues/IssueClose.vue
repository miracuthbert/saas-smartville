<template>
    <ul class="list-inline" :class="{ 'mb-3': !wrapped }">
        <li class="list-inline-item" v-if="!status && issue.owner">
            <a href="#"
               @click.prevent="update"
               v-if="!processing">
                Close
            </a>
            <span v-else>Closing issue...</span>
        </li>
        <li class="list-inline-item" v-else-if="status">
            Closed
            <timeago :since="closedAt" :auto-update="60" v-if="showTime"></timeago>
        </li>
        <li class="list-inline-item" v-else>
            Issue still open
        </li>
    </ul>
</template>

<script>
    import axios from 'axios'
    import Bus from '../../bus'

    export default {
        name: "issue-close",
        props: {
            issue: {
                required: true,
                type: Object
            },
            closeTime: {
                required: false,
                type: Object
            },
            wrapped: {
                required: false,
                type: Boolean,
                default: false
            },
            showTime: {
                required: false,
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                closed: null,
                closedDate: null,
                processing: false
            }
        },
        computed: {
            endpoint() {
                return `/issues/${this.issue.id}/close`
            },
            status() {
                if (this.closed !== null) {
                    return this.closed
                }

                if (typeof this.issue.closed_at === 'object') {
                    return this.issue.closed
                }

                return this.issue.closed_at ? true : false
            },
            closedAt() {
                if (this.closedDate !== null) {
                    return this.closedDate.datetime
                }

                if (typeof this.closeTime === 'object' && this.closeTime.datetime) {
                    return this.closeTime.datetime
                }

                if (typeof this.issue.closed_at === 'object') {
                    return this.issue.closed_at.datetime
                }

                return null
            }
        },
        mounted() {
            Bus.$on('issue:closed', this.updateStatus)
        },
        methods: {
            async updateStatus(issue) {
                if (issue.id === this.issue.id) {
                    this.closed = issue.closed
                    this.closedDate = issue.closed_at
                }
            },
            async update() {
                try {
                    this.processing = true

                    Bus.$emit('issue:closing', {
                        issue: this.issue,
                        status: true
                    })

                    let response = await axios.put(`${this.endpoint}`)

                    toastr.success('Issue has been closed.', this.issue.title)

                    Bus.$emit('issue:closed', response.data.data)
                } catch (e) {
                    if (e.response) {
                        if (e.response.status === 401) {
                            toastr.error('Sorry! You cannot close this issue.', this.issue.title)

                            return
                        }
                    }

                    toastr.error('Whoops! Something went wrong. Failed closing issue.', this.issue.title)
                } finally {
                    Bus.$emit('issue:closing', {
                        issue: this.issue,
                        status: true
                    })

                    this.processing = false
                }
            }
        }
    }
</script>

<style scoped>

</style>