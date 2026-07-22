<template>
  <Dropdown align="right" width="w-24">
    <template #trigger>
      <button
          class="inline-flex items-center rounded-md border-none bg-white px-3 py-2 text-sm leading-4 font-medium text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50"
      >
        {{ languages[locale] }}

        <svg
            class="ms-2 -me-0.5 h-4 w-4"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
        >
          <path
              fill-rule="evenodd"
              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
              clip-rule="evenodd"
          />
        </svg>
      </button>
    </template>

    <template #content>
      <button
          v-for="(language, key) in languages"
          :key="key"
          class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
          @click="changeLanguage(key)"
      >
        {{ language }}
      </button>
    </template>
  </Dropdown>
</template>

<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import Dropdown from '@/Components/Dropdown.vue'

const props = defineProps({
  languages: {
    type: Object,
    default: () => ({
      es: 'Español',
      en: 'English',
    }),
  },
})

const page = usePage()

const locale = ref(page.props.locale)

const { locale: i18nLocale } = useI18n()

const changeLanguage = (selectedLocale) => {
  locale.value = selectedLocale
  i18nLocale.value = selectedLocale

  router.post(route('locale.change', selectedLocale))
}
</script>
