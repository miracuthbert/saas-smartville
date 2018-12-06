<template>
    <small>
        <span class="text-muted" v-if="status">Edited</span>
    </small>
</template>

<script>
    import Bus from '../../bus'

    export default {
        name: "issue-edit-status",
        props: {
            issue: {
                required: true,
                type: Object
            }
        },
        data() {
            return {
                edited: null
            }
        },
        computed: {
            status() {
                if(this.edited !== null) {
                    return this.edited
                }

                if (typeof this.issue.edited_at === 'object') {
                    return this.issue.edited
                }

                return this.issue.edited_at ? true : false
            }
        },
        mounted() {
            Bus.$on('issue:updated', this.updateStatus)
        },
        methods: {
            async updateStatus(issue) {
                if (issue.id === this.issue.id) {
                    this.edited = issue.edited
                }
            }
        }
    }
</script>

<style scoped>

</style>