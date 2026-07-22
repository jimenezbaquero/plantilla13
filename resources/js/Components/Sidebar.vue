<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { useI18n } from "vue-i18n";

const page = usePage()
const { t } = useI18n()

const menu = [
  {
    label: 'users.users',
    href: 'admin.users.index',
  },
  {
    label: 'users.users',
    href: 'admin.users.index',
  },
  {
    label: 'users.users',
    href: 'admin.users.index',
  },
  {
    label: 'users.users',
    href: 'admin.users.index',
  },
]
</script>

<template>
  <aside class="w-64 min-h-screen bg-gray-900 text-white flex flex-col">
    <div class="h-14 flex items-center px-4 border-b border-gray-800">
      <span class="font-bold text-lg">Plantilla 13</span>
    </div>

    <nav class="flex-1 p-2 space-y-1">
      <Link
          v-for="item in menu"
          :key="item.href"
          :href="route(item.href)"
          class="block px-3 py-2 rounded hover:bg-gray-800 transition-colors"
          :class="{
                    'bg-gray-800': route().current(item.route)
                }"
      >
        {{ t(item.label) }}
      </Link>
    </nav>
  </aside>
</template>
