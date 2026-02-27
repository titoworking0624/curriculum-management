<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    chapter:{
        type: Object,
        required:true
    },
    curricula:Object,
    course_name:String,
})

const decoration = props.course_name + "第" + props.chapter.chapter_number + "章"
</script>

<template>
    <ListLayout :title="chapter.name" :decoration="decoration">
        <CreateButton :href="route('curricula.create')">カリキュラム登録</CreateButton>
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
    </ListLayout>

</template>
