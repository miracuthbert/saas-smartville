<template>
    <form class="mb-4" action="#" @submit.prevent="update">
        <div class="form-group">
            <textarea class="form-control" id="body"
                      :rows="textareaHeight"
                      placeholder="your comment..."
                      autofocus="autofocus"
                      v-model="editing.form.body"></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"
                    :disabled="editing.processing">Save changes
            </button>
            <button type="button" class="btn btn-link"
                    :disabled="editing.processing"
                    @click="cancelEditing">Cancel
            </button>
        </div>
    </form>
</template>

<script>
    import validation from '../../mixins/validation'
    import Bus from '../../bus'

    export default {
        name: "comment-edit",
        props: {
            endpoint: {
                required: false,
                type: String,
                default: ''
            },
            comment: {
                require: true,
                type: Object
            }
        },
        data() {
            return {
                editing: {
                    form: {
                        body: this.comment.body
                    }
                }
            }
        },
        computed: {
            updateEndpoint() {
                if (this.endpoint !== '') {
                    return this.endpoint
                }

                return `/comments/${this.comment.id}`
            },
            closed() {
                if (typeof this.issue.closed_at === 'object') {
                    return this.issue.closed
                }

                return this.issue.closed_at ? true : false
            },
            textareaHeight() {
                return Math.max(Math.floor(this.comment.body.split(/\r*\n/).length / 2), 6)
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

                    toastr.success('Comment has been updated.')

                    Bus.$emit('comment:edited', response.data.data)

                    this.clearEditing()
                    this.cancelEditing()
                } catch (e) {
                    if (e.response) {
                        if (e.response.status === 422) {
                            this.editing.errors = e.response.data.errors

                            toastr.error(error.response.data.message, 'Whoops!')

                            return
                        }
                        if (e.response.status === 401) {
                            toastr.error('Sorry! You cannot update this comment.')

                            return
                        }
                    }

                    toastr.error('Failed updating comment.', 'Whoops! Something went wrong')
                } finally {
                    this.editing.processing = false
                }
            },
            async cancelEditing() {
                Bus.$emit('comment:editing-cancelled', this.comment)
            }
        }
    }
</script>

<style scoped>

</style>