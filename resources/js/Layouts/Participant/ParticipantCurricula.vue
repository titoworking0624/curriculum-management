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
              <th v-if="show" class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">コード</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カリキュラム名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">完了日</th>
              <!-- <th v-if="show" class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th> -->
            </tr>
          </thead>
          <tbody>
              <tr v-for="c in curricula" :key="c.curriculum.id">
                  <td class="px-4 py-3">{{c.curriculum.curriculum_code}}</td>
                  <td class="px-4 py-3">{{c.curriculum.name}}</td>
                  <td v-if="c.starting_date" class="px-4 py-3">{{c.starting_date}}</td>
                  <td v-else class="px-4 py-3 text-gray-400">開始前</td>
                  <td v-if="c.completion_date" class="px-4 py-3">{{c.completion_date}}</td>
                  <td v-else class="px-4 py-3 text-gray-400">未完了</td>
                  <!-- <td class="px-4 py-3">
                    <button v-if="!(c.starting_date)"
                        @click="removeChapter(c.curriculum.id)" class="text-red-500 hover:text-red-700">
                        ×
                    </button>
                    </td> -->
              </tr>
          </tbody>
        </table>

</template>
