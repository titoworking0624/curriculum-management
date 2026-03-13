<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import CurriculumList from '@/Layouts/Curriculum/CurriculumList.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Chapter, Course, Curriculum } from '@/types/course';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    chapter:Chapter,
    curricula:Curriculum[],
    course:Course,
}>()

const decoration = props.course.name + "第" + props.chapter.chapter_number + "章"
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
        <CurriculumList :curricula="props.curricula" />
    </ListLayout>

</template>
