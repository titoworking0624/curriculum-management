<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import SubTitle from '@/Components/SubTitle.vue';
import CurriculumList from '@/Layouts/Curriculum/CurriculumList.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import { Chapter, Course, Curriculum } from '@/types/course';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    curriculum:Curriculum,
    chapter:Chapter,
    course:Course,
}>()


const form = useForm({
    id:props.curriculum.id,
    chapter_id:props.chapter.id,
    curriculum_number:props.curriculum.curriculum_number,
    curriculum_code:props.curriculum.curriculum_code,
    name:props.curriculum.name,
    content:props.curriculum.name,
    checklist:props.curriculum.checklist
})
// サブタイトル
const subtitle = props.course.name + "(" + props.course.course_code + ")" + "　" + props.chapter.name

// onUpdated(() => {
//     console.log(form.errors)
// })
</script>

<template>
    <FormLayout title="カリキュラム編集">
        <form @submit.prevent="form.put(route('curricula.update',{curriculum:form.id}))" class="flex flex-col -m-2">
            <div class="relative flex w-full">
                <SubTitle :value="subtitle" />
            </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="curriculum_number" class="leading-7 text-sm text-gray-600" value="カリキュラム順" />
                <input type="number" id="curriculum_number" name="curriculum_number" v-model="form.curriculum_number" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.curriculum_number"/>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="curriculum_code" class="leading-7 text-sm text-gray-600" value="カリキュラムコード" />
                <input type="text" id="curriculum_code" name="curriculum_code" v-model="form.curriculum_code" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.curriculum_code"/>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="name" class="leading-7 text-sm text-gray-600" value="章名" />
                <input type="text" id="name" name="name" v-model="form.name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.name"/>
            </div>
          </div>
          <div class="p-2 w-full">
            <div class="relative">
                <InputLabel for="content" class="leading-7 text-sm text-gray-600" value="内容"/>
                <textarea id="content" name="content" v-model="form.content" class="h-32 w-full resize-none rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-6 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"></textarea>
                <InputError :message="form.errors.content"/>
            </div>
          </div>
          <div class="p-2 w-full">
            <div class="relative">
                <InputLabel for="checklist" class="leading-7 text-sm text-gray-600" value="内容"/>
                <textarea id="checklist" name="checklist" v-model="form.checklist" class="h-32 w-full resize-none rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-6 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"></textarea>
                <InputError :message="form.errors.checklist"/>
            </div>
          </div>
          <div class="p-2 w-full">
            <SubmitButton class="mx-auto mt-8">更新する</SubmitButton>
          </div>
        </form>
        <CurriculumList class="mt-8" :curricula="props.chapter.curricula" :id="form.id"/>
    </FormLayout>
</template>
