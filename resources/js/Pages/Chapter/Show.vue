<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CurriculumList from '@/Layouts/Curriculum/CurriculumList.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Chapter, Course, Curriculum } from '@/types/course';

const props = defineProps<{
    chapter:Chapter,
    curricula:Curriculum[],
    course:Course,
}>()

// サブタイトル
const decoration = props.course.name + "(" + props.chapter.chapter_number + ")"
</script>

<template>
    <ListLayout :title="chapter.name" :decoration="decoration">
        <div class="flex">
            <h2 class="font-medium title-font text-2xl w-full mt-2 pl-4 text-gray-900">カリキュラム一覧</h2>
            <div class="flex w-full">
                <div class="flex w-full pl-4 mb-6">
                    <PrimaryButton class="ml-auto" :href="route('chapters.edit',{chapter:props.chapter.id})">チャプター編集</PrimaryButton>
                </div>
                <CreateButton :href="route('curricula.create',{chapter_id: props.chapter.id,course_id:props.course.id})">カリキュラム登録</CreateButton>
            </div>
        </div>
        <!-- カリキュラムリスト -->
        <CurriculumList :curricula="props.curricula" />
    </ListLayout>

</template>
