<template>
    <div>
        <h3 class="mb-5">Replying to comment</h3>

        <Comment :comment="comment" :links="false"/>

        <form action="#" @submit.prevent="store" id="reply-form">
            <div class="form-group">
                <label for="body">Your reply</label>
                <textarea v-model="creating.form.body" id="body"
                          :class="{'is-invalid': false === fieldState('creating', 'body')}" cols="30" rows="6"
                          class="form-control" autofocus="autofocus"></textarea>

                <div class="invalid-feedback" v-if="false === fieldState('creating', 'body')">
                    {{ invalidFeedback('creating', 'body') }}
                </div>
            </div><!-- /.form-group -->

            <div class="form-group">
                <button type="submit" class="btn btn-primary"
                        :disabled="creating.processing">Reply
                </button>
                <button type="button" class="btn btn-link"
                        :disabled="creating.processing"
                        @click.prevent="cancel">Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios'
    import Comment from './Comment'
    import validation from '../../mixins/validation'
    import Bus from '../../bus'

    export default {
        name: "comment-reply",
        props: {
            endpoint: {
                required: false,
                type: String
            },
            comment: {
                require: true,
                type: Object
            }
        },
        computed: {
            replyEndpoint() {
                if (this.endpoint) {
                    return this.endpoint
                }

                return `/comments/${this.comment.id}/replies`
            }
        },
        components: {
            Comment
        },
        mixins: [
            validation
        ],
        mounted() {
            VueScrollTo.scrollTo('#reply-form', 500)
        },
        methods: {
            async store() {
                try {
                    this.creating.errors = []
                    this.creating.processing = true

                    let response = await axios.post(`${this.replyEndpoint}`, this.creating.form)

                    this.cancel()

                    Bus.$emit('comment:replied', {
                        comment: this.comment,
                        reply: response.data.data
                    })

                    toastr.success('Your reply has been posted.')
                } catch (e) {
                    if (e.response) {
                        if (e.response.status === 422) {
                            this.creating.errors = e.response.data.errors

                            toastr.error(e.response.data.message, 'Whoops!')

                            return
                        }
                    }

                    toastr.error('Failed posting reply.', 'Whoops! Something went wrong')
                } finally {
                    this.creating.processing = false
                }
            },
            async cancel() {
                Bus.$emit('comment:reply-cancelled')
            }
        }
    }
</script>

<style scoped>

</style>