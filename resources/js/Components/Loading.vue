<template>
  <div
    v-if="isLoading"
    class="absolute inset-0 bg-white/70 z-50 flex items-center justify-center"
  >
    {{t('app.loading')}}...
  </div>
</template>

<script setup>
import {useI18n} from "vue-i18n";

const props = defineProps({
  isLoading: {
    type: Boolean,
    default: false
  }
})

const {t} = useI18n()

</script>