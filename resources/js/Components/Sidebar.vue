<template>
  <div v-show="show" ref="sidebarRef" class="sidebar">
    <div class="sidebar-first-column">

      <div class="sidebar-content">
        <SidebarColumn
            :header="header"
            :items="columns[0].items"
            :level = 0
            :selected = "selectedPath[0]?.id?? null"
            @select="selectItem"
        />
      </div>


      <footer class="sidebar-footer">

        <Link
            :href="footer.href"
            :target="footer.target"
            class="sidebar-footer-link"
        >
          {{ footer.text }}
        </Link>

      </footer>
    </div>

    <div class="sidebar-panels">
      <SidebarColumn
          v-for="(column, index) in columns.slice(1)"
          :key="index + 1"
          :items="column.items"
          :title="columnName[index + 1]"
          :level="index + 1"
          :selected = "selectedPath[index + 1]?.id ?? null"
          @select="selectItem"
      />
    </div>

  </div>
</template>

<script setup>
import {computed, onBeforeUnmount, onMounted, ref} from 'vue'
import SidebarColumn from './SidebarColumn.vue'
import {Link, router} from "@inertiajs/vue3";

const props = defineProps({
  show: {
    type: Boolean,
    default: true
  },
  menu: {
    type: Array,
    required: true,
  },
  header: {
    type: Object,
    required: true,
  },
  footer: {
    type: Object,
    required: true,
  },
})

const selectedPath = ref([])
const columnName = ref([])

const columns = computed(() => {

  const result = []

  let items = props.menu
  let level = 0


  while (items?.length) {
    const selected = selectedPath.value[level] ?? null

    result.push({
      items,
    })

    items = selected?.children
    level++
  }
  return result

})

function selectItem(item, level) {
  if(selectedPath.value[level]?.id === item.id) {
    return
  }

  selectedPath.value = selectedPath.value.slice(0, level + 1)
  columnName.value = columnName.value.slice(0, level + 1)
  selectedPath.value[level] = item
  columnName.value[level + 1] = item.label
  if (item.href) {
    selectedPath.value = []
    columnName.value = []
    router.visit(item.href)
  }
}

const sidebarRef = ref(null)

function onClickOutside(event) {
  console.log(sidebarRef.value.contains(event.target))
  if (!props.show) {
    return
  }

  if (sidebarRef.value?.contains(event.target)) {
    return
  }

  selectedPath.value = []
  columnName.value = []
  props.show = false
}

onMounted(() => {
  document.addEventListener('pointerdown', onClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('pointerdown', onClickOutside)
})

</script>

<style src="@/Components/Styles/Sidebar.css" scoped>

</style>
