<template>
    <div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ currency }}</span>
            </div>
            <input v-bind="$attrs"
                   type="number"
                   :name="name"
                   :id="id"
                   class="form-control"
                   :class="{ 'is-invalid': hasError }"
                   v-model="newValue"
                   @input="$emit('input', $event.target.value)">
            <div class="input-group-append">
                <span class="input-group-text">{{ formattedValue }}</span>
            </div>

            <div class="invalid-feedback" v-if="hasError">
                {{ this.error }}
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "price-input",
        props: {
            currency: {
                required: false,
                type: String,
                default: '$'
            },
            name: {
                required: true,
                type: String
            },
            id: {
                required: true,
                type: String
            },
            value: {
                required: false,
                type: String,
                default: ''
            },
            error: {
                required: false,
                type: String,
                default: ''
            }
        },
        computed: {
            formattedValue() {
                if (this.newValue === 'undefined') {
                    return this.value
                }

                return Number(this.newValue / 100).toFixed(2)
            },
            hasError() {
                if (this.error === null || this.error === '') {
                    return false
                }

                return true
            }
        },
        data() {
            return {
                newValue: this.value
            }
        }
    }
</script>

<style scoped>

</style>