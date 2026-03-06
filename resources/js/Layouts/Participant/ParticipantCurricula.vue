<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Chapter, ChapterWithCourseName, CurriculumWithCourseName } from '@/types/course';
import { Head } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        curricula:CurriculumWithCourseName[],
        show?: boolean;
    }>(),
    {
        show: true,
    },
);

const emit = defineEmits<{
    (e:'removeChapter',value: number ):void
}>()

const removeChapter = (id:number) => {
    emit('removeChapter',id)
}

</script>

<template>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
              <th v-if="show" class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">コース名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">章番号</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">章名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カリキュラム名</th>
              <th v-if="show" class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="c in curricula" :key="c.curriculum.id">
                  <td class="px-4 py-3">{{c.courseName}}</td>
                  <td class="px-4 py-3">{{c.chapter.chapter_number}}</td>
                  <td class="px-4 py-3">{{c.chapter.name}}</td>
                  <td class="px-4 py-3">{{c.curriculum.name}}</td>
                  <td class="px-4 py-3">
                    <button
                        @click="removeChapter(c.curriculum.id)" class="text-red-500 hover:text-red-700">
                        ×
                    </button>
                    </td>
              </tr>
          </tbody>
        </table>

</template>
