<!-- 入れ替え可能なチャプターリスト -->
<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Chapter } from '@/types/course';
import { VueElement } from 'vue';
import draggableComponent from 'vuedraggable';

// 親から受け取ったモデル
const listchapters = defineModel<Chapter[]>()

</script>

<template>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">入替</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">順番</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">チャプター(章)名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">詳細</th>
            </tr>
          </thead>
          <draggableComponent v-model="listchapters" tag="tbody" itemKey="id" handle=".cursor-move" animation="200"
            ghost-class="opacity-40"
            chosen-class="bg-yellow-100"
            :force-fallback="true"
            :fallback-on-body="true"
            :delay="50"
            :delay-on-touch-only="true">
              <template #item="{element,index}">
                  <tr>
                      <td class="cursor-move px-4 py-3">☰</td>
                      <td class="px-4 py-3">{{index + 1}}</td>
                      <td class="px-4 py-3">{{element.name}}</td>
                      <td class="px-4 py-3">
                        <PrimaryButton :href="route('chapters.show',{chapter:element.id})">詳細</PrimaryButton>
                      </td>
                  </tr>
              </template>
            </draggableComponent>
            <tbody v-if="!listchapters || listchapters.length === 0">
                <tr>
                    <td class="px-4 py-3" colspan="4">チャプターなし</td>
                </tr>
            </tbody>
        </table>

</template>
