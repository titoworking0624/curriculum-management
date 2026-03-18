<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ChapterList from '@/Layouts/Chapter/ChapterList.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Chapter, Course } from '@/types/course';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    course:Course
    chapters:Chapter[]
}>()
const title = props.course.name + "(" + props.course.course_code + ")"
</script>

<template>
    <ListLayout :title="title" decoration="コース名：">
        <div class="flex">
            <h2 class="font-medium title-font text-2xl w-full mt-2 pl-4 text-gray-900">章一覧</h2>
                            <div class="flex w-full pl-4 mb-6">
                    <PrimaryButton class="ml-auto" :href="route('courses.edit',{course:props.course.id})">コース編集</PrimaryButton>
                </div>
            <CreateButton :href="route('chapters.create',{course_id: props.course.id})">章登録</CreateButton>
        </div>
        <ChapterList :chapters="props.chapters" />
        <div class="flex mt-12">
            <PrimaryButton class="mx-auto" :href="route('courses.index')">コース一覧へ戻る</PrimaryButton>
        </div>

    </ListLayout>

</template>
