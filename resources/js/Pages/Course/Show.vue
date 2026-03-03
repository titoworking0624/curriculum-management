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
        <CreateButton :href="route('chapters.create',{course_id: props.course.id})">章登録</CreateButton>
        <ChapterList :chapters="props.chapters" />
    </ListLayout>

</template>
