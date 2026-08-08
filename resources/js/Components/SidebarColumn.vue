<template>
  <div class="sidebarColumn">
    <header class="sidebarColumn-header flex justify-center items-center">
      <Link v-if="header" :href="header.href" class="flex justify-center items-center">
        <img :id="header.id" class="w-20" :src="header.src" :alt="header.alt"/>
      </Link>
      <h2 v-else class="sidebarColumn-title">{{title}}</h2>
    </header>

    <SidebarItem
        v-for="item in items"
        :key="item.id"
        :item="item"
        :selected="selected === item.id"
        @select="onSelect"
    />

  </div>
</template>

<script setup>
import SidebarItem from './SidebarItem.vue'
import {Link} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
  level: {
    type: Number,
    default: 0,
  },
  header: {
    type: Object,
    default: null
  },
  title:{
    type: String,
    default: ''
  },
  selected:{
    type: String,
    default: ''
  }
})

const emit = defineEmits([
  'select',
])

function onSelect(item) {
  emit('select', item, props.level)
}
</script>

<style src="@/Components/Styles/Sidebar.css" scoped>



</style>
