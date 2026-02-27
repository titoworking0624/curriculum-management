<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    course:{
        type: Object,
        required:true
    },
    chapters:Object,
})
const title = props.course.name + "(" + props.course.course_code + ")"
</script>

<template>
    <ListLayout :title="title" decoration="コース名：">
        <CreateButton :href="route('chapters.create')">章登録</CreateButton>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">詳細</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">章番号</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">章名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="c in chapters">
                  <td class="px-4 py-3">
                      <PrimaryButton :href="route('chapters.show',{chapter:c.id})">詳細</PrimaryButton>
                  </td>
                  <td class="px-4 py-3">{{c.chapter_number}}</td>
                  <td class="px-4 py-3">{{c.name}}</td>
                  <td class="px-4 py-3">
                      <SecondaryButton>編集</SecondaryButton>
                  </td>

              </tr>
          </tbody>
        </table>
    </ListLayout>

</template>
