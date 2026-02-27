<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import HeadLayout from './HeadLayout.vue';

defineProps({
    title:String,
    decoration:String,
})
</script>

<template>
    <Head :title="title"/>
    <HeadLayout />
    <section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-col text-center w-full mb-12">
      <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">
        <span v-if="decoration" class="mr-8">{{ decoration }}</span>
        <span>{{ title }}</span>
    </h1>
    </div>
    <div class="lg:w-2/3 w-full mx-auto">
        <slot />
    </div>
  </div>
</section>
</template>
