<!-- チャプター一覧リスト -->
<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Chapter } from '@/types/course';

const props = withDefaults(
    defineProps<{
        chapters:Chapter[],
        show?: boolean; // 詳細ボタンなどがいるかどうかのフラグ
        id?:number,
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
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">チャプター番号</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">チャプター名</th>
              <th v-if="show" class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="c in chapters" :key="c.id" :class="{'bg-yellow-400':c.id === props.id}">
                  <td v-if="show" class="px-4 py-3">
                      <PrimaryButton :href="route('chapters.show',{chapter:c.id})">詳細</PrimaryButton>
                  </td>
                  <td class="px-4 py-3">{{c.chapter_number}}</td>
                  <td class="px-4 py-3">{{c.name}}</td>
                  <td v-if="show" class="px-4 py-3">
                      <SecondaryButton :href="route('chapters.edit',{chapter:c.id})" v-if="show">編集</SecondaryButton>
                  </td>
              </tr>
              <tr v-if="chapters.length === 0">
                <td class="px-4 py-3" colspan="4">登録チャプターなし</td>
              </tr>
          </tbody>
        </table>

</template>
