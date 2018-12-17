<template>
    <li class="media mb-4"
        :id="`issue-${issue.id}-wrapper`">
        <div class="media-body">
            <div class="mb-1">
                <h5 class="mt-0">
                    <a :href="link">{{ issue.title }}</a>
                </h5>

                <div>
                    <small>
                        Posted by <span class="text-muted">{{ !issue.owner ? issue.user.name : 'You' }}</span>
                        <timeago :since="issue.created_at.datetime" :auto-update="60"></timeago>
                    </small>
                </div>

                <div>
                    <IssueEditStatus :issue="issue"/>
                </div>
            </div>

            <article class="mb-3">{{ issue.body }}</article><!-- todo: limit words shown -->

            <div class="topics mt-1 mb-3">
                <span class="mr-1"><i class="icon-tag"></i></span>

                <span class="topic badge badge-secondary py-1 px-2 mr-1"
                      v-for="topic in issue.topics"
                      :key="topic.id">
                    {{ topic.name }}
                </span>
            </div>

            <ul class="list-inline mb-3">
                <li class="list-inline-item">
                    <i class="icon-speech"></i> 0 Comments
                </li>
                <li class="list-inline-item" v-if="issue.owner && !closeStatus">
                    <a href="#" @click.prevent="destroy" v-if="!deleting">
                        <i class="icon-trash"></i> Delete
                    </a>
                    <span v-else>Deleting...</span>
                </li>
                <li class="list-inline-item" v-if="!deleting">
                    <IssueClose :issue="issue" :wrapped="true" :show-time="true"/>
                </li>
            </ul>

        </div>
    </li>
</template>

<script>
    import axios from 'axios'
    import IssueEditStatus from './IssueEditStatus'
    import IssueClose from './IssueClose'
    import Bus from '../../bus'

    export default {
        name: "issue",
        props: {
            endpoint: {
                required: true,
                type: String
            },
            issue: {
                require: true,
                type: Object
            }
        },
        data() {
            return {
                deleting: false,
                closing: false,
                closed: null
            }
        },
        computed: {
            link() {
                return `${this.endpoint}/${this.issue.id}`
            },
            closeStatus() {
                if (this.closing !== false) {
                    return this.closing
                }

                if (this.closed !== null) {
                    return this.closed
                }

                return this.issue.closed
            }
        },
        components: {
            IssueEditStatus,
            IssueClose
        },
        mounted() {
            Bus.$on('issue:closing', this.issueClosing)
                .$on('issue:closed', this.closeIssue)
        },
        methods: {
            async destroy() {
                try {
                    this.deleting = true

                    let response = await axios.delete(`/issues/${this.issue.id}`)

                    toastr.success(`Issue deleted successfully.`, this.issue.title)

                    this.$emit('issue:deleted', this.issue)
                } catch (e) {
                    // optional: log error to file, db...

                    toastr.danger(`Something went wrong! Failed deleting issue.`, this.issue.title)
                } finally {
                    this.deleting = false
                }
            },
            async issueClosing(issue, status) {
                if (issue.id === this.issue.id) {
                    this.closing = status
                }
            },
            async closeIssue(issue) {
                if (issue.id === this.issue.id) {
                    this.closed = issue.closed
                }
            }
        }
    }
</script>

<style scoped>

</style>