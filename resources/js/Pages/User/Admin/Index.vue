<template>
  <Head :title="t('users.title')"/>

  <AppLayout :isLoading="isLoading || table.loading">

    <div class="h-full flex flex-col bg-white rounded-lg shadow-md p-6">

      <!-- HEADER -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-title">{{ t('users.title') }}</h1>
      </div>

      <!-- TOP BAR -->
      <div class="flex w-full justify-between">
        <div>
          {{ t('datatable.total_registers') }}: {{ table.total }}
        </div>

        <div>
          <button class="btn primary-button" @click="table.cleanFilters()">
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
            :table="table"

            @clickRow="onClickRow"
            @update="onUpdate"
            @delete="onDelete"
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
import {useDatatable} from "@/composables/useDatatable.js"

const props = defineProps({
  users: Object,
  filters: Object,
  columns: Object,
  actions: Object,
  funnelOptions: Object,
})

const {t} = useI18n()
const toast = useToast()

const table = useDatatable({
  data: props.users,
  filters: props.filters,
  columns: props.columns,
  actions: props.actions,
  funnelOptions: props.funnelOptions,
  rowClickable: true,
  dataRoute: 'admin.users.getData',
  fontSize: '0.8rem'
})

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
 * CLICKROW
 */
function onClickRow(id) {
  router.visit(route('admin.users.show', id), {
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
      await table.getData()
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
