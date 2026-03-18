<script setup lang="ts">
import Dropdown from '@/Components/Dropdown.vue';
import { Chapter, ChapterWithCourseName, Course } from '@/types/course';
import { computed } from 'vue';

const props = defineProps<{
    courses:Course[],
}>()

const flatChapters = computed<ChapterWithCourseName[]>(() =>
    props.courses.flatMap((course) =>
        course.chapters.map((chapter) => ({
            ...chapter,
            courseName:course.name,
        })) ?? []
    ) ?? []
)


const emit = defineEmits<{
    (e:'selectChapter',value: ChapterWithCourseName ):void
}>()

const selectChapter = (chapter:ChapterWithCourseName) => {
    emit('selectChapter',chapter)
}

</script>
<template>
    <Dropdown align="left" width="48">
        <template #trigger>
            <div type="button" class="relative inline-flex h-12 w-12 items-center justify-center rounded-md bg-neutral-950 transition-colors  hover:bg-neutral-800"><div><svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-200"><path d="M8.14645 3.14645C8.34171 2.95118 8.65829 2.95118 8.85355 3.14645L12.8536 7.14645C13.0488 7.34171 13.0488 7.65829 12.8536 7.85355L8.85355 11.8536C8.65829 12.0488 8.34171 12.0488 8.14645 11.8536C7.95118 11.6583 7.95118 11.3417 8.14645 11.1464L11.2929 8H2.5C2.22386 8 2 7.77614 2 7.5C2 7.22386 2.22386 7 2.5 7H11.2929L8.14645 3.85355C7.95118 3.65829 7.95118 3.34171 8.14645 3.14645Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path></svg></div></div>
        </template>
        <template #content>
            <!-- <div v-if="!flatChapters.length"></div> -->
            <div >
                <div v-if="!flatChapters.length" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">チャプターが存在していません</div>
                <div v-for="c in flatChapters" :key="c.id" @click="selectChapter(c)"
                    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">
                    {{ c.chapter_number }}： {{ c.name }}
                </div>
            </div>
        </template>
    </Dropdown>
</template>
