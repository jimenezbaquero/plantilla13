<script setup>
import AdminSidebar from '@/Components/AdminSidebar.vue'
import Header from "@/Components/Header.vue";
import FlashNotifications from "@/Components/FlashNotifications.vue";
import Loading from "@/Components/Loading.vue";

const props = defineProps({
  isLoading: {
    type:Boolean,
    default: false
  }
})

console.log(props.isLoading)

</script>

<template>
  <FlashNotifications />
  <div
      class="grid h-screen"
      style="
      grid-template-columns: 16rem 1fr;
      grid-template-rows: 3.5rem 1fr;
      grid-template-areas:
        'sidebar header'
        'sidebar main';
    "
  >
    <aside style="grid-area: sidebar;">
      <AdminSidebar />
    </aside>

    <header style="grid-area: header;">
      <Header class="h-full bg-white border-b flex items-center px-4 justify-end" />
    </header>

    <main
        class="h-full overflow-auto p-4"
        style="grid-area: main;"
    >
      <Loading :isLoading="isLoading" />
      <slot />
    </main>
  </div>
</template>
