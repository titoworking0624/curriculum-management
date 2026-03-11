<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import SubTitle from '@/Components/SubTitle.vue';
import TextInput from '@/Components/TextInput.vue';
import ChapterList from '@/Layouts/Chapter/ChapterList.vue';
import CurriculumList from '@/Layouts/Curriculum/CurriculumList.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import { Chapter, Course, Curriculum } from '@/types/course';
import { useForm } from '@inertiajs/vue3';
import { nl2br } from "@/common";
import CopyButton from '@/Components/CopyButton.vue';

const props = defineProps<{
    curriculum:Curriculum,
    chapter:Chapter,
    course:Course,
}>()

// const form = useForm({
//     id:props.curriculum.id,
//     chapter_id:props.curriculum.chapter_id,
//     curriculum_number:props.curriculum.curriculum_number,
//     curriculum_code:props.curriculum.curriculum_code,
//     name:props.curriculum.name,
//     content:props.curriculum.content,
//     checklist:props.curriculum.checklist
// })
const subtitle = props.course.name + "　" + props.chapter.name
</script>

<template>
    <FormLayout title="カリキュラム詳細">
        <div class="flex flex-col -m-2">
          <div class="p-2">
            <div class="relative flex w-full">
                <SubTitle :value="subtitle" />
                <div class="w-1/2 mt-2 flex">
                    <SecondaryButton class="ml-auto" :href="route('curricula.edit',{curriculum:props.curriculum.id})">編集する</SecondaryButton>
                </div>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel class="leading-7 text-sm text-gray-600" value="カリキュラム順" />
                <div class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ curriculum.curriculum_number }}</div>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel class="leading-7 text-sm text-gray-600" value="カリキュラムコード" />
                <div class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ curriculum.curriculum_code }}　</div>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel class="leading-7 text-sm text-gray-600" value="カリキュラム名" />
                <div class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ curriculum.name }}</div>
            </div>
          </div>
          <div class="p-2 w-full">
            <div class="relative">
                <div class="flex h-10">
                    <InputLabel class="leading-7 text-sm text-gray-600 mt-auto" value="内容"/>
                    <CopyButton v-if="curriculum" :text="curriculum.content" class="ml-auto mt-1 mr-4">コピー</CopyButton>
                </div>
                <div class="h-32 w-full resize-none rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-6 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 whitespace-pre-line">{{ curriculum.content }}</div>
            </div>
          </div>
          <div class="p-2 w-full">
            <div class="relative">
                <InputLabel class="leading-7 text-sm text-gray-600" value="チェックリスト"/>
                <div class="h-32 w-full resize-none rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-6 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 whitespace-pre-line">{{ curriculum.checklist }}</div>
            </div>
          </div>
        </div>
                <CurriculumList class="mt-8" :curricula="props.chapter.curricula" :id="curriculum.id"/>

    </FormLayout>
</template>
