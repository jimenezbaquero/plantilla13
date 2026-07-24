import { ref } from 'vue'
import axios from 'axios'

export function useDatatable(config) {

    const loading = ref(false)

    const data = ref({ ...config.data })
    const filters = ref({ ...config.filters })

    const total = ref(data.value.total)

    async function getData() {

        loading.value = true

        try {

            const response = await axios.post(
                route(config.dataRoute),
                filters.value
            )

            data.value = response.data

        } catch (error) {

            console.error(error)

        } finally {

            loading.value = false

        }

    }

    function onPage(event) {

        filters.value.page = event.page

        return getData()

    }

    function onPerPage(event) {

        filters.value.page = 1
        filters.value.perPage = event.registers

        return getData()

    }

    function onSort(event) {

        filters.value.page = 1
        filters.value[event.col].order_direction = event.order

        return getData()

    }

    function onFilter(event) {

        filters.value.page = 1
        filters.value[event.col].value = event.value

        return getData()

    }

    function onFunnelFilter(event) {

        filters.value[event.col].showFunnel = false
        filters.value.page = 1

        return getData()

    }

    function resetFilters() {

        filters.value.page = 1

        Object.keys(filters.value).forEach(key => {

            if (filters.value[key].value !== undefined) {
                filters.value[key].value = ''
            }

            if (filters.value[key].type === 'funnel') {

                Object.values(filters.value[key].options).forEach(option => {
                    option.checked = false
                })

            }

        })

    }

    function cleanFilters() {

        resetFilters()

        return getData()

    }

    return {
        loading,
        total,
        data,
        filters,

        columns: config.columns,
        actions: config.actions,
        funnelOptions: config.funnelOptions,

        rowClickable: config.rowClickable,
        fontSize: config.fontSize,

        getData,
        onPage,
        onPerPage,
        onSort,
        onFilter,
        onFunnelFilter,
        cleanFilters,
        resetFilters,
    }

}
