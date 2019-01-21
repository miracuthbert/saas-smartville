<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-content-center">
                    <h3>Reminders</h3>

                    <p class="text-right" v-if="!create">
                        <a href="#" @click.prevent="create = !create">Add a new reminder</a>
                    </p>
                </div>
                <hr>

                <!-- New Reminder Component -->
                <NewReminder :endpoint="endpoint" :units="units" :types="types" :expanded="create" :scope="scope"/>
                <hr v-if="create">

                <p v-if="!reminders.length">No reminders found.</p>

                <div class="table-responsive mb-3" v-else>
                    <table class="table table-borderless table-hover mb-0">
                        <thead>
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                    <label class="custom-control-label" for="selectAll">&nbsp;</label>
                                </div>
                            </th>
                            <th>Reminder</th>
                            <th>Type</th>
                            <th>Notify at (time before task)</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="reminder in reminders"
                            :key="reminder.id"
                            :id="`reminder-row-${reminder.id}`">
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           :id="`reminder-${reminder.id}`"
                                           :value="reminder.id">
                                    <label class="custom-control-label" :for="`reminder-${reminder.id}`">&nbsp;</label>
                                </div>
                            </td>
                            <td>{{ reminder.scope }}</td>
                            <td>
                                <span v-for="(type, i) in reminder.types" :key="`reminder-${reminder.id}-${i}`">
                                    {{ type }}
                                </span>
                            </td>
                            <td>
                                <template v-if="editing.id == reminder.id">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="number"
                                                       id="duration_friendly"
                                                       class="form-control"
                                                       :class="{'is-invalid': false === fieldState('editing', 'duration_friendly')}"
                                                       min="1"
                                                       v-model="editing.form.duration_friendly = reminder.duration_friendly">

                                                <div class="input-group-append">
                                                    <select id="unit"
                                                            class="custom-select"
                                                            :class="{'is-invalid': false === fieldState('editing', 'unit')}"
                                                            v-model="editing.form.unit">
                                                        <option v-for="(unit, i) in units"
                                                                :value="i">
                                                            {{ unit.name }}
                                                        </option>
                                                    </select>

                                                    <div class="invalid-feedback"
                                                         v-if="false === fieldState('editing', 'unit')">
                                                        {{ invalidFeedback('editing', 'unit') }}
                                                    </div>
                                                </div>

                                                <div class="invalid-feedback"
                                                     v-if="false === fieldState('editing', 'duration_friendly')">
                                                    {{ invalidFeedback('editing', 'duration_friendly') }}
                                                </div>
                                            </div>
                                        </div>

                                        <small class="help-text">The time the reminder should be sent.</small>
                                    </div>
                                </template>
                                <template v-else>
                                    {{ reminder.duration }}
                                </template>
                            </td>
                            <td>
                                <template v-if="editing.id != reminder.id">
                                    <a href="#" @click.prevent="editing.id = reminder.id" v-if="!editing.processing">
                                        Edit
                                    </a>
                                    <a href="#" @click.prevent="destroy(reminder.id)" v-if="deleting.id != reminder.id">
                                        Delete
                                    </a>
                                    <template v-else>
                                        Deleting...
                                    </template>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-primary" :disabled="editing.processing"
                                            @click.prevent="update">
                                        Update
                                    </button>
                                    <a href="#" class="btn btn-link" :disabled="editing.processing"
                                       @click.prevent="clearEditing">
                                        Cancel
                                    </a>
                                </template>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import NewReminder from './NewReminder'
    import validation from '../../mixins/validation'
    import Bus from '../../bus'

    export default {
        name: "reminders",
        props: {
            endpoint: {
                required: true,
                type: String
            },
            scope: {
                required: true,
                type: String
            }
        },
        data() {
            return {
                create: false,
                reminders: [],
                units: {},
                types: {},
                meta: {}
            }
        },
        components: {
            NewReminder
        },
        mixins: [
            validation
        ],
        mounted() {
            this.loadReminders()
            this.loadUnits()
            this.loadTypes()

            Bus.$on('reminder:created', this.prependToReminders)
                .$on('reminder:create-toggle', (status) => {
                    this.create = status
                })
                .$on('reminder:editing', this.setupEditingForm)
        },
        watch: {
            'editing.id': (newId, oldId) => {
                Bus.$emit('reminder:editing', newId)
            }
        },
        methods: {
            fetchReminders() {
                return axios.get(`${this.endpoint}`)
            },
            async loadReminders() {
                let response = await this.fetchReminders()

                this.reminders = response.data.data
                this.meta = response.data.meta
            },
            async loadUnits() {
                let response = await axios.get('/reminders/units')

                this.units = response.data.data
            },
            async loadTypes() {
                let response = await axios.get('/reminders/types')

                this.types = response.data.data
            },
            async update() {
                try {
                    this.editing.processing = true
                    this.editing.errors = []

                    let response = await axios.put(`/reminders/${this.editing.id}`, this.editing.form)

                    _.assign(_.find(this.reminders, {id: this.editing.id}), response.data.data)

                    toastr.success('Reminder successfully updated.')

                    this.clearEditing()
                } catch (e) {
                    if (e.response) {
                        if (e.response.status === 422) {
                            this.editing.errors = e.response.data.errors

                            toastr.error(e.response.data.message, 'Whoops!')

                            return
                        }
                    }

                    toastr.error('Failed updating reminder.', 'Whoops! Something went wrong')
                } finally {
                    this.editing.processing = false
                }
            },
            async destroy(id) {
                try {
                    this.deleting.id = id
                    this.deleting.processing = true

                    let response = await axios.delete(`/reminders/${id}`)

                    this.reminders = this.reminders.filter((reminder) => reminder.id !== id)

                    toastr.success('Reminder successfully deleted.')

                    this.clearDeleting()
                } catch (e) {
                    if (e.response) {
                        //
                    }

                    toastr.error('Failed deleting reminder.', 'Whoops! Something went wrong')
                } finally {
                    this.deleting.processing = false
                }
            },
            setupEditingForm(newId) {
                let reminder = _.find(this.reminders, {id: newId})

                if (!reminder) {
                    return
                }

                this.editing.form.unit = reminder.unit
            },
            prependToReminders(reminder) {
                this.reminders.unshift(reminder)

                this.scrollToReminder(reminder)

                this.create = false
            },
            scrollToReminder(reminder) {
                setTimeout(() => {
                    VueScrollTo.scrollTo(`#reminder-row-${reminder.id}`, 500)
                }, 100)
            }
        }
    }
</script>

<style scoped>

</style>