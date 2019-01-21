<template>
    <div>
        <form class="mb-4" action="#" @submit.prevent="store" v-if="expanded">
            <h4>Add a new reminder</h4>

            <input type="hidden" name="map" v-model="creating.form.map">

            <div class="form-group">
                <label for="reminder_scope">Reminder</label>
                <select id="reminder_scope"
                        class="custom-select form-control"
                        :class="{'is-invalid': false === fieldState('creating', 'reminder_scope')}"
                        v-model="creating.form.reminder_scope">
                    <option :value="i" v-for="(reminder, i) in scopes">
                        {{ reminder }}
                    </option>
                </select>

                <div class="invalid-feedback" v-if="false === fieldState('creating', 'reminder_scope')">
                    {{ invalidFeedback('creating', 'reminder_scope') }}
                </div>
            </div>

            <div class="form-group">
                <label>Duration</label>

                <div class="form-group">
                    <div class="input-group">
                        <input type="number"
                               id="duration_friendly"
                               class="form-control"
                               :class="{'is-invalid': false === fieldState('creating', 'duration_friendly')}"
                               min="1"
                               v-model="creating.form.duration_friendly">

                        <div class="input-group-append">
                            <select id="unit"
                                    class="custom-select"
                                    :class="{'is-invalid': false === fieldState('creating', 'unit')}"
                                    v-model="creating.form.unit">
                                <option :value="i" v-for="(unit, i) in units">
                                    {{ unit.name }}
                                </option>
                            </select>

                            <div class="invalid-feedback" v-if="false === fieldState('creating', 'unit')">
                                {{ invalidFeedback('creating', 'unit') }}
                            </div>
                        </div>

                        <div class="invalid-feedback" v-if="false === fieldState('creating', 'duration_friendly')">
                            {{ invalidFeedback('creating', 'duration_friendly') }}
                        </div>
                    </div>
                </div>

                <small class="help-text">The time the reminder should be sent.</small>
            </div>

            <div class="form-group">
                <label>Notification types</label>

                <div class="form-group">
                    <div class="custom-control custom-checkbox custom-control-inline"
                         v-for="(type, i) in types"
                         :key="i">
                        <input type="checkbox"
                               class="custom-control-input"
                               :id="`type${i}`"
                               :value="i"
                               v-model="creating.form.type_ids">
                        <label class="custom-control-label"
                               :for="`type${i}`">
                            {{ type }}
                        </label>
                    </div>
                </div>

                <small class="help-text">The notification types (channels) you prefer</small>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary"
                        :disabled="creating.processing">Add reminder
                </button>
                <button type="button" class="btn btn-secondary"
                        :disabled="creating.processing"
                        @click="cancel">Cancel
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
        name: "new-reminder",
        props: {
            endpoint: {
                required: true,
                type: String
            },
            units: {
                required: true,
                type: Object
            },
            types: {
                required: true,
                type: Object
            },
            expanded: {
                type: Boolean,
                default: true
            },
            scope: {
                required: true,
                type: String
            }
        },
        data() {
            return {
                scopes: [],
                creating: {
                    form: {
                        map: this.scope,
                        type_ids: []
                    }
                }
            }
        },
        mixins: [
            validation
        ],
        mounted() {
            this.loadScopes()
        },
        methods: {
            async loadScopes() {
                let response = await axios.get('/reminders/scopes')

                this.scopes = _.get(_.pick(response.data.data, this.scope), this.scope)
            },
            async store() {
                try {
                    this.creating.processing = true
                    this.creating.errors = []

                    let response = await axios.post(this.endpoint, this.creating.form)

                    toastr.success('Reminder successfully created.')

                    Bus.$emit('reminder:created', response.data.data)

                    this.clearCreating()
                    this.creating.form = {
                        type_ids: []
                    }
                } catch (e) {
                    if (e.response) {
                        if (e.response.status === 422) {
                            this.creating.errors = e.response.data.errors

                            toastr.error(e.response.data.message, 'Whoops!')

                            return
                        }
                    }

                    toastr.error('Failed creating reminder.', 'Whoops! Something went wrong')
                } finally {
                    this.creating.processing = false
                }
            },
            cancel() {
                Bus.$emit('reminder:create-toggle', false)
            }
        }
    }
</script>

<style scoped>

</style>