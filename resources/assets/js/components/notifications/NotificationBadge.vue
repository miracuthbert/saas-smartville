<template>
    <sup class="notification-badge-wrapper">
        <sup class="notification-badge bg-primary" v-if="showBadge">&nbsp;</sup>
    </sup>
</template>

<script>
    import Bus from '../../bus'

    export default {
        name: "notification-badge",
        props: {
            count: {
                required: false,
                type: Number,
                default: 0
            },
            endpoint: {
                required: false,
                type: String
            }
        },
        data() {
            return {
                notifyCount: this.count || 0
            }
        },
        computed: {
            showBadge() {
                if (this.notifyCount > 0) {
                    return true
                }

                return false
            }
        },
        mounted() {
            Bus.$on('notifications:loaded', this.updateCount)
            Bus.$on('notification:read', this.updateCount)
            Bus.$on('notification:removed', this.updateCount)
        },
        methods: {
            updateCount(count) {
                this.notifyCount = count
            }
        }
    }
</script>

<style scoped>
    .notification-badge-wrapper {
        display: inline;
    }
    .notification-badge {
        font-size: 10%;
        border-radius: 100%;
        padding: 0.1em 0.5em;
    }
</style>