<script setup>
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import useNotify from "@/composables/useNotify.js";

const page = usePage();
const notify = useNotify();

watch(
    () => page.props.flash,
    (flash) => {
      if (!flash) return;

      flash.success && notify.success(flash.success);
      flash.error && notify.error(flash.error);
      flash.warning && notify.warning(flash.warning);
      flash.info && notify.info(flash.info);
    },
    { deep: true, immediate: true }
);
</script>

<template />
