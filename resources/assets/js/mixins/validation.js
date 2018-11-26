export default {
    data() {
        return {
            creating: {
                form: {},
                errors: [],
                processing: false
            },
            editing: {
                id: null,
                form: {},
                errors: [],
                processing: false
            },
            deleting: {
                id: null,
                form: {},
                errors: [],
                processing: false
            },
            status: {
                id: null,
                form: {},
                errors: [],
                processing: false
            }
        }
    },
    methods: {
        fieldState(action, field) {
            if (action == 'creating' && this.creating.errors[field]) {
                return false
            }
            else if (action == 'editing' && this.editing.errors[field]) {
                return false
            }
            else if (action == 'deleting' && this.deleting.errors[field]) {
                return false
            }
            else if (action == 'status' && this.status.errors[field]) {
                return false
            }

            return null
        },
        invalidFeedback(action, field) {
            if (this.fieldState(action, field) == false) {
                if (action == 'creating') {
                    return this.creating.errors[field][0]
                }
                else if (action == 'editing') {
                    return this.editing.errors[field][0]
                }
                else if (action == 'deleting') {
                    return this.deleting.errors[field][0]
                }
                else if (action == 'status') {
                    return this.status.errors[field][0]
                }

                return 'Whoops! Please enter valid data.'
            }

            return ''
        },
        clearCreating() {
            this.creating.form = {}
            this.creating.errors = []
        },
        clearEditing() {
            this.editing.id = null
            this.editing.form = {}
            this.editing.errors = []
        },
        clearDeleting() {
            this.deleting.id = null
            this.deleting.form = {}
            this.deleting.errors = []
        }
    }
}