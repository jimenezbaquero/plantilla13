<template>
  <FlashNotifications />
  <div
      class="grid h-screen"
      style="
      grid-template-columns: 11rem 1fr;
      grid-template-rows: 3.5rem 1fr;
      grid-template-areas:
        'sidebar header'
        'sidebar main';
    "
  >
    <aside style="grid-area: sidebar;">
      <Sidebar :menu="menu" :footer="footer" :header="header" />
    </aside>

    <header style="grid-area: header;">
      <Header class="h-full bg-white border-b flex items-center px-4 justify-end" />
    </header>

    <main
        class="h-full overflow-auto p-4"
        style="grid-area: main;"
    >
      <div
          v-if="loading"
          class="absolute inset-0 bg-white/70 z-50 flex items-center justify-center"
      >
        Loading...
      </div>
      <slot />
    </main>
  </div>
</template>

<script setup>
import Sidebar from '@/Components/Sidebar.vue'
import Header from "@/Components/Header.vue";
import FlashNotifications from "@/Components/FlashNotifications.vue";
import {useI18n} from "vue-i18n";
import {useAdminMenu} from "@/composables/useAdminMenu.js";

defineProps({
  loading: {
    type:Boolean,
    default: false
  }
})

const { t } = useI18n()

const menu = useAdminMenu()

const header = {
  alt: t('app.menu.logo'),
  src: '/img/logo.svg',
  id: 'logo-image',
  href: route('dashboard'),
}

const footer = {
  text: t('app.footer'),
  href: '/dashboard',
  target: '_blank',
}

</script>
