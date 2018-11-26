<template>
    <fieldset>
        <div class="form-group row" :class="{ 'has-error': errors[this.sendAs]  }">
            <label :for="sendAs" class="control-label col-md-4">Upload an image</label>

            <div class="col-md-6">
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
        </div>

        <div class="form-group" v-if="property.image">
            <input type="hidden" name="image" :value="property.image">

            <img class="img-fluid" :src="property.imageUrl" alt="property image" v-if="showPreview">

            <p>
                <a href="#" @click.prevent="showPreview = !showPreview">
                    {{ showPreview ? 'Hide' : 'Preview' }} image
                </a>
            </p>
        </div>
    </fieldset>
</template>

<script>
    import upload from '../../../mixins/upload'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "property-image-upload",
        props: [
            'propertyImage',
            'imageUrl'
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
                showPreview: false,
                property: {
                    image: this.propertyImage,
                    imageUrl: this.imageUrl
                }
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
                    this.property = response.data.data
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