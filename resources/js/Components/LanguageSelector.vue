<template>
  <select :value="modelValue" @change="changeLanguage">
    <option v-for="(language, key) in languages" :value="key">{{language}}</option>
  </select>
</template>


<script setup>
const props = defineProps({
  languages: {
    type: Object,
    default: () => ({
      'es': 'Español',
      'en': 'English'
    })
  },
  modelValue: {
    type: String,
    required: true
  }
})

const emits = defineEmits(['update:modelValue'])

const changeLanguage = (event) => {
  emits('update:modelValue', event.target.value)
}
</script>



