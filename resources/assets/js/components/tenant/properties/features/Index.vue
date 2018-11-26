<template>
    <div id="tenant-property-features-wrapper">
        <div class="d-flex justify-content-between align-content-center mb-2">
            <p>List of property features.</p>

            <aside>
                <b-link @click.prevent="create = !create">
                    {{ create ? 'Cancel' : 'Add new feature' }}
                </b-link>
            </aside>
        </div>
        <hr>

        <div class="mb-3" v-if="create">
            <b-form @submit.prevent="store">

                <!-- Name -->
                <b-form-group horizontal
                              description="Name of feature."
                              label="Name"
                              label-for="name"
                              :invalid-feedback="invalidFeedback('creating', 'name')">
                    <b-form-input id="name"
                                  max="160"
                                  :state="fieldState('creating', 'name')"
                                  v-model="creating.form.name"></b-form-input>
                </b-form-group>

                <!-- Count -->
                <b-form-group horizontal
                              description="The total no. of this feature within the property."
                              label="Total"
                              label-for="feature-count"
                              :invalid-feedback="invalidFeedback('creating', 'count')">
                    <b-form-input id="feature-count"
                                  max="5"
                                  :state="fieldState('creating', 'count')"
                                  v-model="creating.form.count"></b-form-input>
                </b-form-group>

                <!-- Details -->
                <b-form-group horizontal
                              description="A short description of property feature."
                              label="Details"
                              label-for="feature-details"
                              :invalid-feedback="invalidFeedback('creating', 'details')">
                    <b-form-textarea id="feature-details"
                                     :state="fieldState('creating', 'details')"
                                     v-model="creating.form.details"
                                     :rows="4"
                                     :max-row="6"></b-form-textarea>
                </b-form-group>

                <!-- Buttons -->
                <b-form-group horizontal>
                    <!-- Spinner -->
                    <div class="my-1" v-if="creating.processing">
                        <hollow-dots-spinner
                                :animation-duration="1000"
                                :dot-size="15"
                                :dots-num="3"
                                :color="'#ff1d5e'"
                        />
                        <p>Saving feature...</p>
                    </div>

                    <!-- Buttons -->
                    <template v-else>
                        <!-- Save -->
                        <b-button type="submit" variant="primary">Save</b-button>

                        <!-- Cancel -->
                        <b-button type="button"
                                  variant="secondary"
                                  @click="create = !create">
                            Cancel
                        </b-button>
                    </template>
                </b-form-group>
            </b-form>
            <hr>
        </div>

        <div class="mb-3">
            <p v-if="!fetching && !features.length">No property features found.</p>

            <template v-else>
                <div class="mb-1" v-if="fetching">
                    <hollow-dots-spinner
                            :animation-duration="1000"
                            :dot-size="15"
                            :dots-num="3"
                            :color="'#ff1d5e'"/>
                    <p>Loading...</p>
                </div>

                <b-media class="mb-3" v-for="feature in features" :key="feature.id">
                    <template v-if="editing.id != feature.id">
                        <div class="d-flex justify-content-between align-content-center mb-1">
                            <section>
                                <h5 class="mt-0">{{ feature.name }}</h5>
                                <p>Total: <strong>{{ feature.count }}</strong></p>
                            </section>

                            <aside>
                                <b-link @click.prevent="edit(feature)" v-if="!editing.processing">Edit</b-link>
                                <b-link @click.prevent="destroy(feature)" v-if="deleting.id != feature.id">
                                    Delete
                                </b-link>

                                <!-- Delete progress -->
                                <div v-if="deleting.processing && deleting.id == feature.id">
                                    <hollow-dots-spinner
                                            :animation-duration="1000"
                                            :dot-size="15"
                                            :dots-num="3"
                                            :color="'#ff1d5e'"/>
                                    <p>Deleting...</p>
                                </div>
                            </aside>
                        </div>
                        <div class="py-1 mb-1" v-if="feature.details">
                            {{ feature.details }}
                        </div>
                    </template>
                    <template v-else>
                        <hr v-if="featureIndex(feature) != 0">
                        <b-form @submit.prevent="update(feature)">
                            <p class="h5">Edit feature</p>

                            <!-- Name -->
                            <b-form-group horizontal
                                          description="Name of feature."
                                          label="Name"
                                          label-for="name"
                                          :invalid-feedback="invalidFeedback('editing', 'name')">
                                <b-form-input id="name"
                                              max="160"
                                              :state="fieldState('editing', 'name')"
                                              v-model="editing.form.name"></b-form-input>
                            </b-form-group>

                            <!-- Count -->
                            <b-form-group horizontal
                                          description="The total no. of this feature within the property."
                                          label="Total"
                                          label-for="feature-count"
                                          :invalid-feedback="invalidFeedback('editing', 'count')">
                                <b-form-input id="feature-count"
                                              max="5"
                                              :state="fieldState('editing', 'count')"
                                              v-model="editing.form.count"></b-form-input>
                            </b-form-group>

                            <!-- Details -->
                            <b-form-group horizontal
                                          description="A short description of property feature."
                                          label="Details"
                                          label-for="feature-details"
                                          :invalid-feedback="invalidFeedback('editing', 'details')">
                                <b-form-textarea id="feature-details"
                                                 :state="fieldState('editing', 'details')"
                                                 v-model="editing.form.details"
                                                 :rows="4"
                                                 :max-row="6"></b-form-textarea>
                            </b-form-group>

                            <!-- Buttons -->
                            <b-form-group horizontal>
                                <!-- Spinner -->
                                <div class="my-1" v-if="editing.processing">
                                    <hollow-dots-spinner
                                            :animation-duration="1000"
                                            :dot-size="15"
                                            :dots-num="3"
                                            :color="'#ff1d5e'"
                                    />
                                    <p>Saving feature...</p>
                                </div>

                                <!-- Buttons -->
                                <template v-else>
                                    <!-- Save -->
                                    <b-button type="submit" variant="primary">Save Changes</b-button>

                                    <!-- Cancel -->
                                    <b-button type="button"
                                              variant="secondary"
                                              @click="clearEditing">
                                        Cancel
                                    </b-button>
                                </template>
                            </b-form-group>
                        </b-form>
                    </template>
                </b-media>
            </template>
        </div>
    </div>
</template>

<script>
    import validation from '../../../../mixins/validation'
    import {HollowDotsSpinner} from 'epic-spinners'
    import toastr from 'toastr'

    export default {
        name: "TenantPropertyFeaturesIndex",
        props: [
            'endpoint'
        ],
        components: {
            HollowDotsSpinner
        },
        mixins: [
            validation
        ],
        data() {
            return {
                fetching: false,
                create: false,
                features: [],
                scrollOptions: {
                    easing: 'ease-in-out'
                }
            }
        },
        mounted() {
            this.getFeatures()

            toastr.options = {
                closeOnHover: false,
                preventDuplicates: true
            }
        },
        methods: {
            getFeatures() {
                this.fetching = true

                axios.get(this.endpoint).then((response) => {
                    this.features = response.data.data
                    this.returnToTop()
                }).catch((error) => {
                    // log error to file or call webhook

                    console.log(error)
                }).finally(() => {
                    this.fetching = false
                })
            },
            featureIndex(feature){
                return _.indexOf(this.features, feature)
            },
            store() {
                this.creating.processing = true

                axios.post(this.endpoint, this.creating.form).then((response) => {
                    var feature = response.data.data

                    this.features.push(feature)

                    toastr.success('Feature added successfully.', feature.name)

                    this.create = false
                    this.clearCreating()

                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.creating.errors = error.response.data.errors
                        }

                        toastr.error(error.response.message, 'Whoops')

                        return
                    }

                    console.log(error)

                    toastr.error('Failed adding feature. Please try again!', 'Whoops')
                }).finally(() => {
                    this.creating.processing = false
                })
            },
            edit(feature) {
                this.editing.id = feature.id
                this.editing.form = {
                    name: feature.name,
                    count: feature.count,
                    details: feature.details,
                }
            },
            update(feature) {
                this.editing.processing = true

                axios.put(this.endpoint + '/' + feature.id, this.editing.form).then((response) => {
                    toastr.success('Feature updated successfully.', feature.name)

                    feature.name = this.editing.form.name
                    feature.count = this.editing.form.count
                    feature.details = this.editing.form.details

                    this.clearEditing()
                }).catch((error) => {
                    if (error.response) {
                        if (error.response.status === 422) {
                            this.editing.errors = error.response.data.errors
                        }

                        toastr.error(error.response.message, 'Whoops')

                        return
                    }

                    console.log(error)

                    toastr.error('Failed updating feature. Please try again!', 'Whoops')
                }).finally(() => {
                    this.editing.processing = false
                })
            },
            destroy(feature) {
                this.deleting.id = feature.id
                this.deleting.processing = true

                axios.delete(this.endpoint + '/' + feature.id).then((response) => {
                    toastr.success('Feature deleted successfully.', feature.name)

                    this.clearDeleting()

                    this.features = this.features.filter(function (filtered) {
                        return filtered.id != feature.id
                    })
                }).catch((error) => {
                    if (error.response) {
                        toastr.error(error.response.message, 'Whoops')

                        return
                    }

                    console.log(error)

                    toastr.error('Failed deleting feature. Please try again!', 'Whoops')
                }).finally(() => {
                    this.deleting.processing = false
                })
            },
            returnToTop() {
                this.$scrollTo('#tenant-property-features-wrapper', 500, this.scrollOptions)
            }
        }
    }
</script>

<style scoped>

</style>