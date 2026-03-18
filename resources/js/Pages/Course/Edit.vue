<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import SubTitle from '@/Components/SubTitle.vue';
import ChapterDraggableList from '@/Layouts/Chapter/ChapterDraggableList.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import { Chapter, Course } from '@/types/course';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    chapters:Chapter[],
    course:Course,
    errors:object,
}>()

// コース内チャプターID一覧
const chaptersId = props.chapters.map(c => ({id:c.id}))

// チャプター一覧リスト
const listChapters = ref([...props.chapters]);

const form = useForm({
    id:props.course.id,
    code:props.course.course_code,
    name:props.course.name,
    chapters:chaptersId,
})

// リスト内順番更新
const updateFormOrder = () =>{
    form.chapters = listChapters.value.map(c => ({
        id:c.id
    }))
}

</script>

<template>
    <FormLayout title="コース編集">
        <form @submit.prevent="form.put(route('courses.update',{course:form.id}))" class="flex flex-col -m-2">
            <div class="relative flex w-full">
                <PrimaryButton class="ml-auto" :href="route('courses.index')">コース一覧へ戻る</PrimaryButton>
            </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="code" class="leading-7 text-sm text-gray-600" value="コースコード(英字2文字)" />
                <input type="text" id="code" name="code" v-model="form.code" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.code"/>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="name" class="leading-7 text-sm text-gray-600" value="コース名" />
                <input type="text" id="name" name="name" v-model="form.name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.name"/>
            </div>
          </div>
          <div class="p-2 w-full">
            <div class="relative">
                <div class="flex mt-12">
                    <h3 class="inline-flex font-medium text-gray-700 ml-2 items-end">コース内チャプター一覧</h3>
                </div>
                <!-- 入れ替え可能なチャプターリスト -->
                <ChapterDraggableList
                    v-model="listChapters"
                    @end="updateFormOrder"/>
            </div>
          </div>
          <div class="p-2 w-full">
            <SubmitButton class="mx-auto mt-8">更新する</SubmitButton>
          </div>
        </form>
    </FormLayout>
</template>
