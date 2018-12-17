<template>
    <div>
        <h4 v-if="showHeading">Issues</h4>

        <template v-if="issues.length">
            <ul class="list-unstyled">
                <Issue v-for="issue in issues"
                       :key="issue.id"
                       :issue="issue"
                       :endpoint="endpoint"
                       v-on:issue:closed="closeIssue"
                       v-on:issue:deleted="removeIssue"/>
            </ul>
        </template>

        <p v-else>
            No issues found
        </p>

        <a href="#"
           class="btn btn-light btn-block shadow-none"
           @click.prevent="loadMore"
           v-if="meta.current_page < meta.last_page">
            Show more
        </a>
    </div>
</template>

<script>
    import axios from 'axios'
    import Issue from './Issue'
    import Bus from '../../bus'

    export default {
        name: "issues",
        props: {
            endpoint: {
                required: true,
                type: String,
            },
            showHeading: {
                required: false,
                type: Boolean,
                default: true,
            }
        },
        data() {
            return {
                issues: [],
                meta: {},
            }
        },
        components: {
            Issue
        },
        mounted() {
            Bus.$on('issue:created', this.prependToIssues)
                .$on('issue:closed', this.closeIssue)

            this.loadIssues()
        },
        methods: {
            fetchIssues(page = 1) {
                return axios.get(`${this.endpoint}?page=${page}`)
            },
            async loadIssues(page = 1) {
                let response = await this.fetchIssues(page)

                this.issues = response.data.data
                this.meta = response.data.meta

                Bus.$emit('issues:loaded', this.meta.total)
            },
            async fetchMeta() {
                let response = await this.fetchIssues(this.meta.current_page)

                this.meta = response.data.meta

                Bus.$emit('issues:loaded', this.meta.total)
            },
            async loadMore() {
                let response = await this.fetchIssues(this.meta.current_page + 1)

                this.issues.push(...response.data.data)
                this.meta = response.data.meta

                Bus.$emit('issues:loaded', this.meta.total)
            },
            async loadOneAfterDeletion() {
                if (this.issues.length === 0 && this.meta.last_page < this.meta.current_page) {
                    this.loadIssues(this.meta.last_page)

                    return
                }

                if (this.meta.current_page >= this.meta.last_page) {
                    return
                }

                let response = await this.fetchIssues(this.meta.current_page)

                this.issues.push(response.data.data[response.data.data.length - 1])
                this.meta = response.data.meta

                Bus.$emit('issues:loaded', this.meta.total)
            },
            async closeIssue(issue) {
                _.assign(_.find(this.issues, {id: issue.id}), issue)
            },
            async removeIssue(issue) {
                this.issues = this.issues.filter((node) => {
                    return node.id !== issue.id
                })

                this.meta.total--

                Bus.$emit('issue:removed', this.meta.total)

                await this.loadOneAfterDeletion()
            },
            async prependToIssues(issue) {
                this.issues.unshift(issue)

                await this.fetchMeta()

                this.scrollToIssue(issue)

                if(this.meta.current_page < this.meta.last_page) {
                    this.issues.pop()
                }
            },
            async scrollToIssue(issue) {
                setTimeout(() => {
                    VueScrollTo.scrollTo(`#issue-${issue.id}-wrapper`, 500)
                }, 100)
            }
        }
    }
</script>

<style scoped>

</style>