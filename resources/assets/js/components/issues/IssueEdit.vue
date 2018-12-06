<template>
    <div>
        <template v-if="!edit">
            <div class="mb-3">{{ issueBody }}</div>

            <p class="mb-2" v-if="issue.owner && !closed">
                <a href="#" @click.prevent="edit = !edit">Edit</a>
            </p>
        </template>

        <form class="mb-4" action="#" @submit.prevent="update" v-else>
            <div class="form-group">
                <textarea class="form-control" id="body" placeholder="details of issue..."
                          v-model="editing.form.body" autofocus=""></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-sm btn-primary"
                        :disabled="editing.processing">Save changes
                </button>
                <button type="button" class="btn btn-sm btn-link"
                        :disabled="editing.processing"
                        @click="edit = !edit">Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios'
    import validation from '../../mixins/validation'
    import Bus from '../../bus'

    export default {
        name: "issue-edit",
        props: {
            issue: {
                required: true,
                type: Object
            },
            endpoint: {
                required: false,
                type: String,
                default: ''
            }
        },
        data() {
            return {
                edit: false,
                issueBody: this.issue.body,
                editing: {
                    form: {
                        body: this.issue.body
                    }
                }
            }
        },
        computed: {
            updateEndpoint() {
                if (this.endpoint !== '') {
                    return this.endpoint
                }

                return `/issues/${this.issue.id}`
            },
            closed() {
                if (typeof this.issue.closed_at === 'object') {
                    return this.issue.closed
                }

                return this.issue.closed_at ? true : false
            }
        },
        mixins: [
            validation
        ],
        methods: {
            async update() {
                try {
                    this.editing.processing = true

                    let response = await axios.put(`${this.updateEndpoint}`, this.editing.form)

                    this.issueBody = this.editing.form.body

                    toastr.success('Issue has been updated.', this.issue.title)

                    Bus.$emit('issue:updated', response.data.data)

                    this.edit = false
                } catch (e) {
                    if (e.response) {
                        if (e.response.status === 422) {
                            this.editing.errors = e.response.data.errors

                            toastr.error(error.response.message, 'Whoops!')

                            return
                        }
                        if (e.response.status === 401) {
                            toastr.error('Sorry! You cannot update this issue.')

                            return
                        }
                    }

                    toastr.error('Failed updating issue.', 'Whoops! Something went wrong')
                } finally {
                    this.editing.processing = false
                }
            }
        }
    }
</script>

<style scoped>

</style>