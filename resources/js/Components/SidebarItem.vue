<template>
  <button
      @click="select(item)"
      :class="[
      'sidebarItem',
      { 'sidebarItem--selected': selected }
    ]"
  >
    <div class="sidebarItem-content">

      <div class="sidebarItem-left">
        <component
            v-if="item.icon"
            :is="item.icon"
            class="sidebarItem-icon"
        />

        <span class="sidebarItem-label">
          {{ t(item.label) }}
        </span>
      </div>

      <ChevronRightIcon
          v-if="item.children?.length"
          class="sidebarItem-arrow"
      />

    </div>
  </button>
</template>

<script setup>
import {ChevronRightIcon} from '@heroicons/vue/24/outline'
import {useI18n} from "vue-i18n";

const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
  selected: {
    type: Boolean,
    default: false,
  },
})

const { t } = useI18n()

const emits = defineEmits([
  'select',
])

const select = (item) => {
  emits('select', item)
}
</script>

<style src="@/Components/Styles/Sidebar.css" scoped>

</style>
