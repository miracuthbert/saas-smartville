<template>
    <div>
        <div class="card mb-3">
            <div class="card-body">
                <h4>Utilities Settings</h4>

                <div class="alert alert-warning" v-if="meta.default">
                    You are using default settings. Change them to suite your preferences.
                </div>

                <div class="table-responsive" v-if="!mapIsEmpty">
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(setting, key) in map" :key="key">
                            <th scope="row">{{ setting.label }}</th>
                            <td>
                                <template v-if="setting.values != null">
                                    <select class="custom-control custom-select" v-model="settings[key]">
                                        <option v-for="(value, i) in setting.values" :value="i">
                                            {{ value }}
                                        </option>
                                    </select>
                                </template>
                                <template v-else>
                                    <input :type="setting.type || 'text'"
                                           class="form-control"
                                           min="1"
                                           v-model="settings[key]">
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="button" class="btn btn-primary" @click="store" :disabled="saving">
                                    Update
                                </button>
                                <button type="button"
                                        class="btn btn-success"
                                        @click="resetToDefault"
                                        :disabled="saving ? saving : resetting"
                                        v-if="!meta.default">
                                    Reset to default
                                </button>
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
    export default {
        name: "utilities-settings",
        props: {
            endpoint: {
                required: true,
                type: String
            }
        },
        data() {
            return {
                map: {},
                mapMeta: {},
                settings: {},
                meta: {},
                saving: false,
                resetting: false
            }
        },
        computed: {
            mapIsEmpty() {
                return _.isEmpty(this.map)
            }
        },
        mounted() {
            this.loadSettings()
            this.loadSettingsMap()
        },
        methods: {
            async loadSettingsMap() {
                let response = await axios.get('/utilities/settings/map')

                this.map = response.data.data
                this.mapMeta = response.data.meta
            },
            async loadSettings() {
                let response = await axios.get(`${this.endpoint}`)

                this.settings = response.data.data
                this.meta = response.data.meta
            },
            async store() {
                try {
                    this.saving = true

                    let response = await axios.post(`${this.endpoint}`, this.settings)

                    this.meta.default = false

                    toastr.success('Settings updated successfully.')

                    VueScrollTo.scrollTo('body', 500)
                } catch (e) {
                    if (e.response) {
                        toastr.error('Failed updating settings. Please try again!', 'Whoops!')
                    }
                } finally {
                    this.saving = false
                }
            },
            async resetToDefault() {
                try {
                    this.resetting = true

                    let response = await axios.post(`${this.endpoint}/reset`, this.settings)

                    this.setDefaults()

                    toastr.success('Settings set back to default.')

                    VueScrollTo.scrollTo('body', 500)
                } catch (e) {
                    if (e.response) {
                        toastr.error('Failed resetting to default. Please try again!', 'Whoops!')
                    }
                } finally {
                    this.resetting = false
                }
            },
            setDefaults() {
                this.meta.default = true
                this.settings = this.mapMeta.defaults
            }
        }
    }
</script>

<style scoped>

</style>