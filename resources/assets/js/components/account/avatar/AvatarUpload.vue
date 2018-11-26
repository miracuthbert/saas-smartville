<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors[this.sendAs]  }">
            <label :for="sendAs" class="control-label">Upload an image</label>

            <!-- Spinner -->
            <div class="py-2 my-1" v-if="uploading">
                <hollow-dots-spinner
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        :color="'#ff1d5e'"/>
                <p>Processing...</p>
            </div>

            <input :id="sendAs" type="file"
                   class="form-control"
                   :class="{ 'is-invalid': errors[this.sendAs]  }"
                   :name="sendAs"
                   @change="fileChange" v-else>

            <div class="invalid-feedback" v-if="errors[this.sendAs]">
                <strong>{{ errors[sendAs][0] }}</strong>
            </div>
        </div>

        <div class="form-group" v-if="user.avatar">
            <input type="hidden" name="avatar_id" :value="user.id">
            <img class="avatar" :src="user.avatar" alt="Current avatar">
        </div>
    </div>
</template>

<script>
    import upload from '../../../mixins/upload'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "avatar-upload",
        props: [
            'currentAvatar'
        ],
        components: {
            HollowDotsSpinner
        },
        mixins: [
            upload
        ],
        data() {
            return {
                errors: [],
                user: {
                    id: null,
                    avatar: this.currentAvatar
                },
            }
        },
        mounted() {
            toastr.options = {
                closeButton: true,
                preventDuplicates: true
            }
        },
        methods: {
            fileChange(e) {
                this.upload(e).then((response) => {
                    this.user = response.data.data
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors
                        return
                    }

                    this.errors = 'Something went wrong. Try again.'
                    toastr.error(this.errors, 'Whoops')
                })
            }
        }
    }
</script>

<style scoped>

</style>