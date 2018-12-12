<template>
    <div>
        <p v-if="!expanded">
            Have a problem? <a href="#" @click.prevent="expanded = !expanded">Post an issue</a>
        </p>

        <div v-else>
            <h4>Post a new issue</h4>

            <form class="mb-4" action="#" @submit.prevent="store">
                <div class="form-group">
                    <label for="title">Issue <sup>*</sup></label>
                    <input type="text" class="form-control" id="title" placeholder="issue..."
                           v-model="creating.form.title" :autofocus="autofocus">
                </div>

                <div class="form-group">
                    <label for="body">Details of issue <sup>*</sup></label>
                    <textarea class="form-control" id="body" placeholder="details of issue..."
                              v-model="creating.form.body"></textarea>
                </div>

                <div class="form-group">
                    <label for="property_id">Property <sup>*</sup></label>
                    <select id="property_id"
                            class="custom-select form-control"
                            v-model="creating.form.property_id"
                            @change="changed">
                        <option :value="property.id" v-for="property in properties">
                            {{ property.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group" v-if="amenities.length">
                    <label>Amenities</label>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox custom-control-inline"
                             v-for="amenity in amenities"
                             :key="amenity.id">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   :id="'amenity' + amenity.id"
                                   :value="amenity.id"
                                   v-model="amenity_ids">
                            <label class="custom-control-label"
                                   :for="'amenity' + amenity.id">
                                {{ amenity.name }}
                            </label>
                        </div>
                    </div>

                    <small class="help-text">Choose an amenity related to the issue</small>
                </div>

                <div class="form-group" v-if="utilities.length">
                    <label>Utilities</label>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox custom-control-inline"
                             v-for="utility in utilities"
                             :key="utility.id">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   :id="'utility' + utility.id"
                                   :value="utility.id"
                                   v-model="utility_ids">
                            <label class="custom-control-label"
                                   :for="'utility' + utility.id">
                                {{ utility.name }}
                            </label>
                        </div>
                    </div>

                    <small class="help-text">Choose an utility related to the issue</small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"
                            :disabled="creating.processing">Post
                    </button>
                    <button type="button" class="btn btn-secondary"
                            :disabled="creating.processing"
                            @click="expanded = !expanded">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import validation from '../../mixins/validation'
    import Bus from '../../bus'

    export default {
        name: "new-property-issue",
        props: {
            endpoint: {
                required: true,
                type: String
            },
            isExpanded: {
                required: false,
                type: Boolean,
                default: false
            },
            autofocus:{
                required: false,
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                properties: [],
                amenities: [],
                amenity_ids: [],
                utilities: [],
                utility_ids: [],
                property: null,
                expanded: this.isExpanded
            }
        },
        watch: {
            property() {
                if (!this.property) {
                    return
                }

                this.amenities = this.property.amenities
                this.utilities = this.property.utilities
            }
        },
        mixins: [
            validation
        ],
        mounted() {
            this.fetchProperties()
        },
        methods: {
            async fetchProperties() {
                let response = await axios.get(`${this.endpoint}/create`)

                this.properties = response.data.data
            },
            async store() {
                try {
                    this.creating.form.amenity_ids = this.amenity_ids
                    this.creating.form.utility_ids = this.utility_ids

                    this.creating.errors = []
                    this.creating.processing = true

                    let response = await axios.post(`${this.endpoint}`, this.creating.form)

                    toastr.success('Your issue has been posted.')

                    Bus.$emit('issue:created', response.data.data)

                    this.clearCreating()
                    this.expanded = false

                    this.property = null
                    this.amenities = []
                    this.utilities = []
                    this.utility_ids = []
                    this.amenity_ids = []
                } catch (e) {
                    if (e.response) {
                        if (e.response.status === 422) {
                            this.creating.errors = e.response.data.errors

                            toastr.error(e.response.data.message, 'Whoops!')

                            return
                        }
                    }

                    toastr.error('Failed posting issue.', 'Whoops! Something went wrong')
                } finally {
                    this.creating.processing = false
                }
            },
            changed(event) {
                this.property = _.find(this.properties, {id: this.creating.form.property_id || event.target.value})
            }
        }
    }
</script>

<style scoped>

</style>