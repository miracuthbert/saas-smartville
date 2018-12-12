<template>
    <div>
        <a href="#"
           class="btn btn-primary btn-block"
           @click.prevent="active = true"
           v-if="!active">
            Post a comment
        </a>

        <template v-if="active">
            <form action="#" @submit.prevent="store">
                <div class="form-group">
                    <label for="body">Your comment</label>
                    <textarea v-model="creating.form.body" id="body"
                              :class="{'is-invalid': false === fieldState('creating', 'body')}" cols="30" rows="10"
                              class="form-control" autofocus="autofocus"></textarea>

                    <div class="invalid-feedback" v-if="false === fieldState('creating', 'body')">
                        {{ invalidFeedback('creating', 'body') }}
                    </div>
                </div><!-- /.form-group -->

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"
                            :disabled="creating.processing">Post
                    </button>
                    <button type="button" class="btn btn-link"
                            :disabled="creating.processing"
                            @click.prevent="active = false">Cancel
                    </button>
                </div>
            </form>
        </template>
    </div>
</template>

<script>
    import axios from 'axios'
    import validation from '../../mixins/validation'
    import Bus from '../../bus'

    export default {
        name: "new-comment",
        props: {
            endpoint: {
                required: true,
                type: String
            }
        },
        data() {
            return {
                active: false
            }
        },
        mixins: [
            validation
        ],
        methods: {
            async store() {
                try {
                    this.creating.errors = []
                    this.creating.processing = true

                    let response = await axios.post(`${this.endpoint}`, this.creating.form)

                    Bus.$emit('comment:stored', response.data.data)

                    toastr.success('Your comment has been posted.')

                    this.clearCreating()
                    this.active = false
                } catch (e) {
                    if (e.response) {
                        if (e.response.status === 422) {
                            this.creating.errors = e.response.data.errors

                            toastr.error(e.response.data.message, 'Whoops!')

                            return
                        }
                    }

                    toastr.error('Failed posting comment.', 'Whoops! Something went wrong')
                } finally {
                    this.creating.processing = false
                }
            }
        }
    }
</script>

<style scoped>

</style>