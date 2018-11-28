<template>
    <div id="contact-form-wrapper">
        <h3 :class="headingStyles">{{ heading }}</h3>

        <form action="#" autocomplete="false" @submit.prevent="store">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" v-model="creating.form.name" class="form-control"
                       :class="{'is-invalid': false === fieldState('creating', 'name')}" id="name"
                       :autofocus="autofocus">

                <div class="invalid-feedback" v-if="false === fieldState('creating', 'name')">
                    {{ invalidFeedback('creating', 'name') }}
                </div>
            </div><!-- /.form-group -->

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" v-model="creating.form.email" class="form-control"
                       :class="{'is-invalid': false === fieldState('creating', 'email')}" id="email">

                <div class="invalid-feedback" v-if="false === fieldState('creating', 'email')">
                    {{ invalidFeedback('creating', 'email') }}
                </div>
            </div><!-- /.form-group -->

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" v-model="creating.form.subject" class="form-control"
                       :class="{'is-invalid': false === fieldState('creating', 'subject')}" id="subject">

                <div class="invalid-feedback" v-if="false === fieldState('creating', 'subject')">
                    {{ invalidFeedback('creating', 'subject') }}
                </div>
            </div><!-- /.form-group -->

            <div class="form-group">
                <label for="message">Message</label>
                <textarea v-model="creating.form.message" id="message"
                          :class="{'is-invalid': false === fieldState('creating', 'message')}" cols="30" rows="5"
                          class="form-control"></textarea>

                <div class="invalid-feedback" v-if="false === fieldState('creating', 'message')">
                    {{ invalidFeedback('creating', 'message') }}
                </div>
            </div><!-- /.form-group -->

            <div class="form-group">
                <button class="btn btn-block btn-primary" :disabled="sending">Send</button>
            </div>
        </form>
    </div>
</template>

<script>
    import validation from '../../mixins/validation'

    export default {
        name: "contact",
        props: {
            endpoint: {
                required: true,
                type: String
            },
            heading: {
                required: false,
                type: String,
                default: 'Leave us a message'
            },
            headingStyles: {
                required: false,
                type: Array,
                default: function () {
                    return []
                }
            },
            autofocus: {
                required: false,
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                sending: false
            }
        },
        mixins: [
            validation
        ],
        methods: {
            async store() {
                try {
                    this.creating.errors = []
                    this.sending = true

                    let response = await axios.post(`${this.endpoint}`, this.creating.form)

                    toastr.success('Your message was successfully sent. We will reach out to you shortly.', 'Success')

                    this.clearCreating()
                } catch (e) {
                    if (e.response) {
                        if (e.response.status === 422) {
                            this.creating.errors = e.response.data.errors

                            toastr.error(error.response.message, 'Whoops!')

                            return
                        }
                    }

                    toastr.error('Failed sending message.', 'Whoops! Some went wrong')
                } finally {
                    this.sending = false

                    VueScrollTo.scrollTo('#contact-form-wrapper', 500)
                }
            }
        }
    }
</script>

<style scoped>

</style>