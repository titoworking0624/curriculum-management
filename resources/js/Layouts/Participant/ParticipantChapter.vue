<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Chapter, ChapterWithCourseName } from '@/types/course';
import { Head } from '@inertiajs/vue3';
import draggableComponent from 'vuedraggable';

const props = defineProps<{
    fixedChapters:ChapterWithCourseName[]
}>()

const emit = defineEmits<{
    (e:'removeChapter',value: number ):void
}>()

const removeChapter = (id:number) => {
    emit('removeChapter',id)
}

const listChapters = defineModel<ChapterWithCourseName[]>()

</script>

<template>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ソート</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">順番</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">チャプター(章)名</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">章番号</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">コース名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">詳細</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">削除</th>
            </tr>
          </thead>

            <draggableComponent v-model="listChapters" tag="tbody" itemKey="id" handle=".cursor-move" animation="200"
              ghost-class="opacity-40"
              chosen-class="bg-yellow-100"

              :force-fallback="true"
              :fallback-on-body="true"
              :delay="50"
              :delay-on-touch-only="true">
              <template #header>
                <tr v-for="(chapter,index) in props.fixedChapters"
                    :key="chapter.id"
                    class="bg-gray-100">
                      <td class="px-4 py-3">×</td>
                      <td class="px-4 py-3">{{index + 1}}</td>
                      <td class="px-4 py-3">
                        {{chapter.name}}</td>
                        <td class="px-4 py-3">{{chapter.chapter_number}}</td>
                        <td class="px-4 py-3">{{chapter.courseName}}</td>
                        <td class="px-4 py-3">
                            <PrimaryButton :href="route('chapters.show',{chapter:chapter.id})">詳細</PrimaryButton>
                        </td>
                        <td v-if="!chapter.isStarting" class="px-4 py-3">
                            <button
                            @click="removeChapter(chapter.id)" class="text-red-500 hover:text-red-700">
                            ×
                        </button>
                    </td>
                </tr>
              </template>
              <template #item="{element,index}">
                  <tr>
                      <td class="cursor-move px-4 py-3">☰</td>
                      <td class="px-4 py-3">{{index + fixedChapters.length + 1}}</td>
                      <td class="px-4 py-3">
                        {{element.name}}</td>
                        <td class="px-4 py-3">{{element.chapter_number}}</td>
                        <td class="px-4 py-3">{{element.courseName}}</td>
                        <td class="px-4 py-3">
                            <PrimaryButton :href="route('chapters.show',{chapter:element.id})">詳細</PrimaryButton>
                        </td>
                        <td v-if="!element.isStarting" class="px-4 py-3">
                            <button
                            @click="removeChapter(element.id)" class="text-red-500 hover:text-red-700">
                            ×
                        </button>
                    </td>
                </tr>
            </template>
          </draggableComponent>
    </table>

</template>
