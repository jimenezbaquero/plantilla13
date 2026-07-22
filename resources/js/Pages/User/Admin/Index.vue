<template>
  <Head :title="t('users.title')"/>

  <AppLayout :isLoading="isLoading" @resetFilters="resetFilters">

    <div class="h-full flex flex-col bg-white rounded-lg shadow-md p-6">

      <!-- HEADER -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-title">{{ t('users.title') }}</h1>
      </div>

      <!-- TOP BAR -->
      <div class="flex w-full justify-between">
        <div>
          {{ t('datatable.total_registers') }}: {{ usersCopy.total }}
        </div>

        <div>
          <button class="btn primary-button" @click="cleanFilters">
            {{ t('datatable.clean_filters') }}
          </button>

          <Link
              :href="route('admin.users.index')"
              class="ml-2 btn primary-button"
          >
            {{ t('users.create') }}
          </Link>
        </div>
      </div>

      <hr class="mt-6 mb-0">

      <!-- DATATABLE -->
      <div class="flex-1 min-h-0">
        <datatable
            :data="usersCopy"
            :filters="filtersCopy"
            :columns="props.columns"
            :actions="props.actions"
            :funnelOptions="funnelOptions"
            :is-row-clickable="true"
            route-after-click='admin.users.index'

            @funnel-filter="onFunnelFilter"
            @per-page-change="onPerPage"
            @page-change="onPage"
            @sort-change="onSort"
            @filter-change="onFilter"
            @update="onUpdate"
            @delete="onDelete"

            fontSize="0.8rem"
        />
      </div>
      <!-- DELETE MODAL -->
      <ConfirmModal
          v-if="showConfirmDeleteModal"
          :show="showConfirmDeleteModal"
          :title="t('users.delete_title')"
          :message="t('users.delete_confirm')"
          @confirm="confirmDelete"
          @cancel="cancelDelete"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import {ref, onBeforeUnmount} from 'vue'
import {Head, Link, router} from '@inertiajs/vue3'
import axios from 'axios'
import {useToast} from "vue-toastification"
import {useI18n} from "vue-i18n"

import AppLayout from "@/Layouts/AppLayout.vue"
import Datatable from "@/Components/Datatable.vue"
import ConfirmModal from "@/Components/ConfirmModal.vue"

const props = defineProps({
  users: Object,
  filters: Object,
  columns: Object,
  actions: Object,
  funnelOptions: Object,
})

const {t} = useI18n()
const toast = useToast()

const usersCopy = ref({...props.users})
const filtersCopy = ref({...props.filters})

const isLoading = ref(false)

const showConfirmDeleteModal = ref(false)
const registerToDelete = ref(null)

/**
 * UPDATE
 */
function onUpdate(id) {
  router.visit(route('admin.users.index', id), {
    preserveState: true,
    preserveScroll: true,
  })
}

/**
 * DELETE
 */
function onDelete(id) {
  showConfirmDeleteModal.value = true
  registerToDelete.value = id
}

/**
 * PAGINATION
 */
function onPage(event) {
  filtersCopy.value.page = event.page
  getData()
}

/**
 * FUNNEL FILTER
 */
function onFunnelFilter(event) {
  console.log(filtersCopy.value[event.col])
  filtersCopy.value[event.col].showFunnel = false
  filtersCopy.value.page = 1
  getData()
}

/**
 * PER PAGE
 */
function onPerPage(event) {
  filtersCopy.value.page = 1
  filtersCopy.value.perPage = event.registers
  getData()
}

/**
 * SORT
 */
function onSort(event) {
  filtersCopy.value.page = 1
  filtersCopy.value[event.col].order_direction = event.order
  getData()
}

/**
 * FILTER TEXT
 */
function onFilter(event) {
  filtersCopy.value.page = 1
  filtersCopy.value[event.col].value = event.value
  getData()
}

/**
 * GET DATA
 */
const getData = async () => {

  isLoading.value = true

  await axios.post(route('admin.users.getData'), filtersCopy.value)
      .then(response => {
        usersCopy.value = response.data
      })
      .catch(error => {
        console.log(error)
      })
      .finally(() => {
        isLoading.value = false
      })
}

/**
 * CLEAN FILTERS
 */
function cleanFilters() {
  resetFilters()
  getData()
}

/**
 * RESET FILTERS
 */
function resetFilters() {

  filtersCopy.value.page = 1

  Object.keys(props.filters).forEach(key => {
    filtersCopy.value[key].value = ''

    if (filtersCopy.value[key].type === 'funnel') {

      Object.keys(filtersCopy.value[key].options).forEach(option => {
        filtersCopy.value[key].options[option].checked = false
      })
    }
  })
}

/**
 * DELETE CONFIRM
 */
async function confirmDelete() {

  isLoading.value = true

  try {

    const response = await axios.delete(route('users.destroy', {
      user: registerToDelete.value
    }))

    if (response.data.success) {
      toast.success(response.data.message)
      showConfirmDeleteModal.value = false
      getData()
    } else {
      toast.error(response.data.message)
    }

  } catch (e) {
    toast.error(t('app.delete_error'))
  } finally {
    isLoading.value = false
  }
}

/**
 * CANCEL DELETE
 */
function cancelDelete() {
  registerToDelete.value = null
  showConfirmDeleteModal.value = false
}

/**
 * CLEANUP
 */
onBeforeUnmount(() => {
  isLoading.value = false
})
</script>

<style scoped src="@/../css/crud.css"></style>
