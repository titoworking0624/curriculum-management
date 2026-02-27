<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    chapter:{
        type: Object,
        required:true
    },
    curricula:Object,
    course_name:String,
})

const title = props.course_name + "第" + props.chapter.chapter_number + "章"
</script>

<template>
    <Head :title="title" />
    <HeadLayout />

<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-col text-center w-full mb-10">
      <h1 class="sm:text-4xl text-3xl font-medium title-font text-gray-900">
        <span>{{ title }}　</span>
        <span>{{chapter.name}}</span>
    </h1>
    </div>
    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
      <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
          <tr>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">詳細</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">順番</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題番号</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">コース名</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
          </tr>
        </thead>
        <tbody>
            <tr v-for="c in curricula">
                <td class="px-4 py-3">
                    <PrimaryButton :href="route('curricula.show',{curriculum:c.id})">詳細</PrimaryButton>
                </td>
                <td class="px-4 py-3">{{c.curriculum_number}}</td>
                <td class="px-4 py-3">{{c.curriculum_code}}</td>
                <td class="px-4 py-3">{{c.name}}</td>
                <td class="px-4 py-3">
                    <SecondaryButton>編集</SecondaryButton>
                </td>

            </tr>
        </tbody>
      </table>
    </div>
    <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">
      <a class="text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0">Learn More
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"></path>
        </svg>
      </a>
      <button class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Button</button>
    </div>
  </div>
</section>
</template>
