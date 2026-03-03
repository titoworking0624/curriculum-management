<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import CurriculumList from '@/Layouts/Curriculum/CurriculumList.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import { Chapter, Course, Curriculum } from '@/types/course';
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    courses: Course[];
    chapter: Chapter | null;
    errors: Object;
}>();

const form = useForm({
    chapter_id: props.chapter?.id ?? 0,
    curriculum_number: 0,
    curriculum_code: "",
    name: null,
    content: null,
    checklist: null,
});
const selectedCourseId = ref(props.chapter?.course_id ?? null);

watch(selectedCourseId, () => {
    form.chapter_id = 0;
});

const selectedCourse = computed<Course | null>(() => {
    return props.courses.find(
        c => c.id === Number(selectedCourseId.value)
    ) ?? null
})

const filteredChapters = computed<Chapter[]>(() => {
    return selectedCourse.value?.chapters ?? [];
});

// const filteredChapters = computed<Chapter[]>(() => {
//     const course = props.courses.find((c) => c.id === selectedCourseId.value);

//     return course?.chapters ?? [];
// });

const filteredCurricula = computed<Curriculum[]>(() => {
    const chapter = filteredChapters.value.find(
        (c) => c.id === form.chapter_id,
    );
    const curricula = chapter?.curricula ?? [];
    form.curriculum_number = curricula.length + 1

    return chapter?.curricula ?? [];
});

const storeCurriculum = () => {
    if(form.curriculum_code === ""){
        const courseCode = selectedCourse.value?.course_code ?? ""

        form.curriculum_code = courseCode + '-' + form.chapter_id + '-' + form.curriculum_number
    }
    form.post('/curricula')
}
</script>

<template>
    <FormLayout title="カリキュラム作成">
        <form
            @submit.prevent="storeCurriculum"
            class="-m-2 flex flex-col"
        >
            <div class="p-2">
                <div class="relative">
                    <InputLabel
                        for="course"
                        class="text-sm leading-7 text-gray-600"
                        value="使用コース"
                    />
                    <select
                        name="course"
                        id="course"
                        v-model="selectedCourseId"
                    >
                        <option
                            v-for="c in courses"
                            :key="c.id"
                            :value="c.id"
                            :selected="c.id === selectedCourseId"
                        >
                            {{ c.course_code }}：{{ c.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="p-2">
                <div class="relative">
                    <InputLabel
                        for="chapter"
                        class="text-sm leading-7 text-gray-600"
                        value="使用章"
                    />
                    <select
                        name="chapter"
                        id="chapter"
                        v-model="form.chapter_id"
                    >
                        <option
                            v-for="c in filteredChapters"
                            :key="c.id"
                            :value="c.id"
                            :selected="c.id === form.chapter_id"
                        >
                            {{ c.chapter_number }}：{{ c.name }}
                        </option>
                    </select>
                </div>
            </div>
            <CurriculumList :curricula="filteredCurricula" :show="false" />
            <div class="p-2">
                <div class="relative">
                    <InputLabel
                        for="curriculum_number"
                        class="text-sm leading-7 text-gray-600"
                        value="カリキュラム順"
                    />
                    <input
                        type="number"
                        id="curriculum_number"
                        name="curriculum_number"
                        v-model="form.curriculum_number"
                        class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                    />
                    <InputError :message="form.errors.curriculum_number" />
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
            <div class="p-2">
                <div class="relative">
                    <InputLabel
                        for="name"
                        class="text-sm leading-7 text-gray-600"
                        value="カリキュラム名"
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
