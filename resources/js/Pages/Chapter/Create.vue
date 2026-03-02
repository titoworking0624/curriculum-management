<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ChapterList from '@/Layouts/Chapter/ChapterList.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

type Chapter = {
    id: number
    name:string
    chapter_number:number
    course_id:number
}

type Course = {
    id:number
    name:string
    course_code:number
    chapters:Chapter[]
}

const props = defineProps<{
    courses:Course[],
    course:Course,
    errors:Object,
    }>()

const form = useForm({
    course_id:props.course?.id ??null,
    chapter_number:null,
    name:null,
})

// const selectedCourseId = ref(form.course_id);

const filteredChapters = computed(() => {
    const course = props.courses.find(
        c => c.id === form.course_id
    )

    return course?.chapters ?? []
})
</script>

<template>
    <FormLayout title="章作成">
        <form @submit.prevent="form.post('/chapters')" class="flex flex-col -m-2">
          <div class="p-2">
            <div class="relative">
                <InputLabel for="course" class="leading-7 text-sm text-gray-600" value="使用コース" />
                <select name="course" id="course" v-model="form.course_id">
                    <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.course_code }}：{{ c.name }}</option>
                </select>
            </div>
          </div>
          <ChapterList :chapters="filteredChapters" :show="false"/>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="chapter_number" class="leading-7 text-sm text-gray-600" value="章順" />
                <input type="number" id="chapter_number" name="chapter_number" v-model="form.chapter_number" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.chapter_number"/>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="name" class="leading-7 text-sm text-gray-600" value="章名" />
                <input type="text" id="name" name="name" v-model="form.name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.name"/>
            </div>
          </div>
          <!-- <div class="p-2 w-full">
            <div class="relative">
              <label for="message" class="leading-7 text-sm text-gray-600">Message</label>
              <textarea id="message" name="message" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
            </div>
          </div> -->
          <div class="p-2 w-full">
            <SubmitButton class="mx-auto mt-8">登録する</SubmitButton>
          </div>
        </form>
    </FormLayout>
</template>
