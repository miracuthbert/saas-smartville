export default {
    props: [
        'endpoint'
    ],
    data() {
        return {
            filters: {},
            selectedFilters: _.omit(this.$route.query, ['page'])
        }
    },
    computed: {
        filtersInUse() {
            return !_.isEmpty(this.selectedFilters)
        }
    },
    mounted() {
        this.getFilters()

        this.$root.$emit('filters-loaded', this.selectedFilters)
            .$on('filters-added', this.activateFilter)
            .$on('filters-removed', this.clearFilter)
            .$on('filters-clear', this.clearFilters)
    },
    methods: {
        getFilters() {
            axios.get(this.endpoint).then((response) => {
                this.filters = response.data.data
            })
        },
        activateFilter(key, value) {
            this.selectedFilters = Object.assign({}, this.selectedFilters, {[key]: value});

            this.$root.$emit('filters-changed', this.selectedFilters)

            this.updateQueryString()
        },
        clearFilter(key) {
            this.selectedFilters = _.omit(this.selectedFilters, key)

            this.$root.$emit('filters-changed', this.selectedFilters)

            this.updateQueryString()
        },
        clearFilters() {
            this.selectedFilters = {}

            this.$root.$emit('filters-cleared')

            this.updateQueryString()
        },
        updateQueryString() {
            this.$router.replace({
                query: {
                    ...this.selectedFilters,
                    page: 1
                }
            })
        }
    }
}