<!-- 受講者編集画面 -->
<script setup lang="ts">
import CancelButton from '@/Components/CancelButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ChapterDropdown from '@/Components/Participant/ChapterDropdown.vue';
import CourseDropdown from '@/Components/Participant/CourseDropdown.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import CurrrentCurriculumList from '@/Layouts/Participant/CurrrentCurriculumList.vue';
import ParticipantChapterList from '@/Layouts/Participant/ParticipantChapterList.vue';
import ParticipantCurriculaList from '@/Layouts/Participant/ParticipantCurriculaList.vue';
import { ChapterWithCourseName, Course, NextCurriculum, ParticipantCurriculum, ParticipantWithRelations } from '@/types/course';
import { router, useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    courses: Course[];
    participant:ParticipantWithRelations,
    currentCurriculum:ParticipantCurriculum | null;
    prevCurriculum:ParticipantCurriculum | null;
    nextCurriculum:NextCurriculum | null;
    errors: Object;
}>();

const form = useForm({
    chapters:[] as number[],
    name: props.participant.name,
    email:props.participant.email,
});

const state = ref<string>()
// 開始済みのチャプターID一覧
// const initialChapterIds = new Set(
//   props.participant.participant_curricula
//     .flatMap(pc => pc.participant_chapter.chapter.id)
// )
// 開始済みのチャプターID一覧
const initialChapterIds = computed(() =>
  new Set(
    props.participant.participant_chapters
      .filter(pc => pc.starting_date)
      .map(pc => pc.chapter.id)
  )
)

// チャプター一覧
const allChapters = computed<ChapterWithCourseName[]>(() =>
  props.courses.flatMap(course =>
    course.chapters.map(ch => ({
      ...ch,
      courseName: course.name,
      isStarting: initialChapterIds.value.has(ch.id),
    //   starting_date: props.participant.participant_chapters
    //     .find(c => c.chapter.id === ch.id)?.starting_date,
      completion_date: props.participant.participant_chapters
        .find(c => c.chapter.id === ch.id)?.completion_date
    }))
  )
)

// 選択したチャプター一覧
const selectedChapters = ref<ChapterWithCourseName[]>([])

// 未開始のチャプター一覧
const draggableChapters = ref<ChapterWithCourseName[]>([])

// 開始済みのチャプター一覧
const fixedChapters = computed(() =>
  selectedChapters.value.filter(c => c.isStarting)
)

// 整列済みのチャプター一覧（開始済み→未開始の順番）
const orderedChapters = computed(() => [
  ...fixedChapters.value,
  ...draggableChapters.value
])
onMounted(() => {

  // 登録チャプターID一覧
  const chapterIds =
    props.participant.participant_chapters.map(pc => pc.chapter.id)

  // 登録チャプター一覧
  const chapters =
    allChapters.value.filter(ch => chapterIds.includes(ch.id))

  // 選択したチャプター一覧に登録チャプター一覧を代入
  selectedChapters.value = chapters

  // 未開始のチャプター一覧
  draggableChapters.value =
    chapters.filter(c => !c.isStarting)

  if(props.currentCurriculum){
    state.value = "課題進捗：" + "課題取り組み中"
  }else if(!props.nextCurriculum){
    state.value = "課題進捗：" + "課題未登録"
  }else if(props.prevCurriculum){
    state.value = "課題進捗：" + "課題提出完了"
  }else{
    state.value = "課題進捗：" + "次課題未スタート"
  }

})

// 登録済みチャプターを削除
const removeChapter = (chapterId:number) => {

  // 削除するチャプターIDを除いたフィルターをかける
  draggableChapters.value =
    draggableChapters.value.filter(c => c.id !== chapterId)

}

// 選択したコースのチャプター一覧を全てチャプターリストに追加
const handleSelectCourse = (id:number) => {

  const course = props.courses.find(c => c.id === id)
  if(!course) return

  for(const chapter of course.chapters){

    // チャプターリストに既に存在していない場合
    if(!orderedChapters.value.some(c => c.id === chapter.id)){

      // 未開始チャプター一覧に追加
      draggableChapters.value.push({
        ...chapter,
        courseName: course.name
      })
    }
  }
}

// 選択されたコース
const selectedCourses = ref<Course[]>([...props.courses])

// 選択されたコースID
const selectedCourseId = ref<number | null>(null)

// コース選択された場合
watch(selectedCourseId, (id) => {
  const course = props.courses.find(c => c.id === id)
  if (course) {
    selectedCourses.value = [course]
  }else{
    selectedCourses.value = props.courses
  }
})

// チャプター選択からチャプターをリストに追加
const handleSelectChapter = (chapter: ChapterWithCourseName) => {

  // チャプターリストにチャプターが存在していない場合
  if(!orderedChapters.value.some(c => c.id === chapter.id)){
    draggableChapters.value.push(chapter)
  }

}

// 受講者情報更新
const updateParticipant = () => {

  // 受講者登録チャプターID一覧
  form.chapters = orderedChapters.value.map(c => c.id)

  form.put(
    route('participants.update',{participant:props.participant.id})
  )

}
</script>

<template>
    <FormLayout title="受講者編集">
        <form
        @submit.prevent="updateParticipant"
        class="-m-2 flex flex-col"
        >
        <div class="p-2 w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200">
            <div class="relative">
                <div class="flex mt-2">
                    <h3 class="block font-medium text-gray-700 ml-2">受講者名</h3>
                    <PrimaryButton class="ml-auto mb-2" :href="route('participants.show',{participant:participant.id})">課題確認</PrimaryButton>
                </div>
                <input
                    type="text"
                    id="name"
                    name="name"
                    v-model="form.name"
                    class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                />
                <InputError :message="form.errors.name" />
            </div>
            <div class="relative">
                <div class="flex mt-2">
                    <h3 class="block font-medium text-gray-700 ml-2">メールアドレス</h3>
                </div>
                <input
                    type="text"
                    id="email"
                    name="email"
                    v-model="form.email"
                    class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                />
                <InputError :message="form.errors.email" />
            </div>
        </div>
        <div class="p-2 w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 my-2">
            <div class="relative">
                <div class="flex my-3">
                    <h3 class="block font-medium text-gray-700 ml-2">{{ state }}</h3>
                    <template v-if="props.currentCurriculum">
                        <div class="ml-auto">
                            <PrimaryButton class="mr-2" :href="route('participants.show',{participant:participant.id})">課題確認</PrimaryButton>
                            <CancelButton @click="router.patch(route('stopCurriculum',{participant:props.participant.id}))" class="mr-auto">課題停止</CancelButton>
                        </div>
                    </template>
                    <template v-else>
                        <div class="ml-auto">
                            <PrimaryButton v-if="props.nextCurriculum"
                                @click="router.patch(route('startCurriculum',{participant:props.participant.id}))" class="mr-2">課題スタート</PrimaryButton>
                            <CancelButton v-if="props.prevCurriculum"
                                @click="router.patch(route('cancelComplete',{participant:props.participant.id}))" class="mr-auto">提出完了キャンセル</CancelButton>
                        </div>
                    </template>
                </div>
                <!-- 課題一覧 -->
                <CurrrentCurriculumList
                    :curricula="props.participant.participant_curricula"
                    :currentCurriculum="props.currentCurriculum"
                    :nextCurriculum="props.nextCurriculum"
                    :prevCurriculum="props.prevCurriculum"/>
            </div>
        </div>

        <div class="p-2 w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 my-2">
            <div class="flex justify-between my-2 flex-col" >
                <h3 class="block font-medium text-gray-700 ml-2">受講チャプター</h3>
                <div class="mt-2">
                    <h4 class="text-sm leading-7 block font-medium text-gray-700 ml-2">チャプター追加</h4>
                    <div class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 flex">
                        <div>
                            <InputLabel :label="false"
                            class="text-sm leading-7 text-gray-600 mr-4"
                            >コース選択</InputLabel>
                            <!-- コース内チャプター全リスト追加 -->
                            <CourseDropdown :courses="courses" @selectCourse="handleSelectCourse"/>
                        </div>
                        <div class="relative flex ml-auto mr-4">
                            <div>
                                <InputLabel :label="false"
                                class="text-sm leading-7 text-gray-600 mr-4"
                                >コース内チャプター追加</InputLabel>
                                <!-- 選択チャプターのコース選択 -->
                                <select
                                name="course"
                                id="course"
                                v-model="selectedCourseId"
                                class="mr-2"
                                >
                                    <option :value="null"></option>
                                    <option
                                        v-for="c in courses"
                                        :key="c.id"
                                        :value="c.id"
                                    >
                                        {{ c.course_code }}：{{ c.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <InputLabel :label="false"
                                class="text-sm leading-7 text-gray-600 mr-4"
                                >チャプター選択</InputLabel>
                                <!-- チャプターリスト追加 -->
                                <ChapterDropdown :courses="selectedCourses" @selectChapter="handleSelectChapter"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <div class="relative">
                    <InputLabel
                    :label="false"
                    class="text-sm leading-7 text-gray-600"
                    value="チャプターリスト"
                    />
                    <!-- 登録チャプター一覧 -->
                    <ParticipantChapterList
                    v-model="draggableChapters"
                    :fixedChapters="fixedChapters"
                    @removeChapter="removeChapter"
                    class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200" />
                </div>
            </div>
        </div>
        <div class="p-2 w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 my-2">
            <div class="relative">
                <h3 class="block font-medium leading-7  text-gray-700 my-2">受講カリキュラム履歴</h3>
                <!-- <InputLabel
                    :label="false"
                    class="text-sm leading-7 text-gray-600"
                    value="受講カリキュラム"
                /> -->
                <!-- 課題一覧 -->
                <ParticipantCurriculaList :curricula="props.participant.participant_curricula"
                    class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 mb-2" />
            </div>
        </div>
        <div class="w-full p-2">
            <SubmitButton class="mx-auto mt-8">更新する</SubmitButton>
        </div>
    </form>
</FormLayout>
</template>
