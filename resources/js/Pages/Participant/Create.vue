<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import CourseDropdown from '@/Components/Participant/CourseDropdown.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import CurriculumList from '@/Layouts/Curriculum/CurriculumList.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import ParticipantChapter from '@/Layouts/Participant/ParticipantChapter.vue';
import { Chapter, ChapterWithCourseName, Course, Curriculum } from '@/types/course';
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    courses: Course[];
    // chapter: Chapter | null;
    errors: Object;
}>();

const form = useForm({
    // chapter_id: props.chapter?.id ?? 0,
    curriculum_number: 0,
    curriculum_code: "",
    name: null,
    content: null,
    checklist: null,
});

// const storeCurriculum = () => {
    //     if(form.curriculum_code === ""){
        //         const courseCode = selectedCourse.value?.course_code ?? ""

        //         form.curriculum_code = courseCode + '-' + form.chapter_id + '-' + form.curriculum_number
        //     }
        //     form.post('/curricula')
        // }

const selectedCourses = ref<Course[]>([])
const selectedChapters = ref<ChapterWithCourseName[]>([])


// const flatChapters = props.courses.flatMap((course) =>
//   course.chapters.map((chapter) => ({
//     ...chapter,
//     courseName: course.name,
//   }))
// )

const handleSelectCourse = (id: number) => {
  const course = props.courses.find(c => c.id === id)

  if (!course) return

  const toChapterWithCourseName = course.chapters.map((chapter) => ({
    ...chapter,
    courseName: course.name,
  }))
//   console.log(chapters)

  for(const chapter of toChapterWithCourseName){
    // console.log(chapter)
    // const chapterId = chapter.id
    // console.log(chapterId)
    if(!selectedChapters.value.some(c => c.id === chapter.id)){
        selectedChapters.value.push(chapter)
    }
  }

//   if (!selectedCourses.value.some(c => c.id === id)) {
//     selectedCourses.value.push(course)
//   }
}
const removeCourse = (id: number) => {
  selectedCourses.value = selectedCourses.value.filter(c => c.id !== id)
}

</script>

<template>
    <FormLayout title="受講者登録">
        <form
            @submit.prevent=""
            class="-m-2 flex flex-col"
        >
        <div class="p-2">
            <div class="relative">
                <InputLabel
                    for="name"
                    class="text-sm leading-7 text-gray-600"
                    value="受講者名"
                />
                <input
                    type="text"
                    id="name"
                    name="name"
                    v-model="form.name"
                    class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                />
                <InputError :message="form.errors.name" />
            </div>
        </div>
            <div class="flex h-16 justify-between">
                <div class="relative ms-3">
                    <CourseDropdown :courses="courses" @selectCourse="handleSelectCourse"/>
                </div>
            </div>
            <div class="p-2">
                <div class="relative">
                    <InputLabel
                        for="curriculum_number"
                        class="text-sm leading-7 text-gray-600"
                        value="受講カリキュラム"
                    />
                    <ParticipantChapter :chapters="selectedChapters"
                        class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                    >
            </ParticipantChapter>
                </div>
            </div>
            <div class="p-2">
                <div class="relative">
                    <InputLabel
                        for="curriculum_code"
                        class="text-sm leading-7 text-gray-600"
                        value="カリキュラムコード(任意)"
                    />
                    <input
                        type="text"
                        id="curriculum_code"
                        name="curriculum_code"
                        v-model="form.curriculum_code"
                        class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                    />
                    <InputError :message="form.errors.curriculum_code" />
                </div>
            </div>
            <div class="w-full p-2">
                <div class="relative">
                    <InputLabel
                        for="content"
                        class="text-sm leading-7 text-gray-600"
                        value="内容"
                    />
                    <textarea
                        id="content"
                        name="content"
                        v-model="form.content"
                        class="h-32 w-full resize-none rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-6 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                    ></textarea>
                    <InputError :message="form.errors.content" />
                </div>
            </div>
            <div class="w-full p-2">
                <div class="relative">
                    <InputLabel
                        for="checklist"
                        class="text-sm leading-7 text-gray-600"
                        value="チェックリスト"
                    />
                    <textarea
                        id="checklist"
                        name="checklist"
                        v-model="form.checklist"
                        class="h-32 w-full resize-none rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-6 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                    ></textarea>
                    <InputError :message="form.errors.checklist" />
                </div>
            </div>
            <div class="w-full p-2">
                <SubmitButton class="mx-auto mt-8">登録する</SubmitButton>
            </div>
        </form>
    </FormLayout>
</template>
