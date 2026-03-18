<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import SubTitle from '@/Components/SubTitle.vue';
import ChapterList from '@/Layouts/Chapter/ChapterList.vue';
import CurriculumDraggableList from '@/Layouts/Curriculum/CurriculumDraggableList.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import { Chapter, Course, Curriculum } from '@/types/course';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    chapter:Chapter,
    course:Course,
    curricula:Curriculum[],
    errors:object,
}>()

// チャプター内カリキュラムID一覧
const curriculaId = props.curricula.map(c => ({ id: c.id }))

// カリキュラムリスト
const listCurricula = ref([...props.curricula])

const form = useForm({
    id:props.chapter.id,
    course_id:props.chapter.course_id,
    chapter_number:props.chapter.chapter_number,
    name:props.chapter.name,
    curricula:curriculaId,
})

const subtitle = "コース：" + props.course.name

// フォームの順番を更新
const updateFormOrder = () => {
  form.curricula = listCurricula.value.map(c => ({ id: c.id }))
//   console.log(form.curricula)
}

</script>

<template>
    <FormLayout title="チャプター編集">
        <form @submit.prevent="form.put(route('chapters.update',{chapter:form.id}))" class="flex flex-col -m-2">
            <div class="relative flex w-full">
                <SubTitle :value="subtitle" class="w-2/3"/>
                <PrimaryButton class="ml-auto" :href="route('chapters.show',{chapter:props.chapter.id})">チャプター一覧へ戻る</PrimaryButton>
            </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="chapter_number" class="leading-7 text-sm text-gray-600" value="チャプター番号" />
                <input type="number" id="chapter_number" name="chapter_number" v-model="form.chapter_number" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.chapter_number"/>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="name" class="leading-7 text-sm text-gray-600" value="チャプター名" />
                <input type="text" id="name" name="name" v-model="form.name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.name"/>
            </div>
          </div>

          <div class="p-2">
              <div class="relative">
                  <div class="flex mt-12">
                      <h3 class="inline-flex font-medium text-gray-700 ml-2 items-end">チャプター内カリキュラム一覧</h3>
                    </div>
                    <!-- 順番入れ替え可能なカリキュラムリスト -->
                    <CurriculumDraggableList class="mt-4" v-model="listCurricula" @end="updateFormOrder"/>
                </div>
            </div>
            <div class="p-2 w-full">
                <SubmitButton class="mx-auto mt-8">更新する</SubmitButton>
            </div>
        </form>
          <div class="mt-4">
              <h3 class="inline-flex font-medium text-gray-700 ml-2 items-end">チャプターリスト</h3>
              <ChapterList :chapters="props.course.chapters" :show="false" :id="props.chapter.id"/>
          </div>
    </FormLayout>
</template>
