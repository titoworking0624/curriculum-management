<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Curriculum } from '@/types/course';
import { Head } from '@inertiajs/vue3';

const props = withDefaults(
    defineProps<{
        curricula:Curriculum[],
        show?: boolean,
        id?: number,
    }>(),
    {
        show: true,
        id:0,
    },
);

</script>

<template>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
              <th v-if="show" class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">詳細</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">順番</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題番号</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">コース名</th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="c in curricula" :class="{'bg-yellow-400':c.id === props.id}">
                  <td v-if="show" class="px-4 py-3">
                      <PrimaryButton :href="route('curricula.show',{curriculum:c.id})">詳細</PrimaryButton>
                  </td>
                  <td class="px-4 py-3">{{c.curriculum_number}}</td>
                  <td class="px-4 py-3">{{c.curriculum_code}}</td>
                  <td class="px-4 py-3">{{c.name}}</td>
              </tr>
              <tr v-if="curricula.length === 0">
                <td class="px-4 py-3" colspan="3">未登録</td>
                <!-- <td class="px-4 py-3">なし</td>
                <td class="px-4 py-3">なし</td> -->
              </tr>
          </tbody>
        </table>

</template>
