<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ChapterDropdown from '@/Components/Participant/ChapterDropdown.vue';
import CourseDropdown from '@/Components/Participant/CourseDropdown.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import ParticipantChapterList from '@/Layouts/Participant/ParticipantChapterList.vue';
import { ChapterWithCourseName, Course } from '@/types/course';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    courses: Course[];
    errors: Object;
}>();

// 選択されたチャプター一覧リスト
const selectedChapters = ref<ChapterWithCourseName[]>([])
// 選択されたコース
const selectedCourses = ref<Course[]>([...props.courses])
// 選択されたコースID
const selectedCourseId = ref<number | null>(null)

const form = useForm({
    chapters:selectedChapters,
    name: null,
});

// チャプター一覧のコース選択
const handleSelectCourse = (id: number) => {
    // 選択されたコース取得
    const course = props.courses.find(c => c.id === id)

    if (!course) return

    // コース内チャプター一覧取得
    const toChapterWithCourseName = course.chapters.map((chapter) => ({
        ...chapter,
        courseName: course.name,
    }))

    // チャプター一覧をリストに追加
    for(const chapter of toChapterWithCourseName){
        if(!selectedChapters.value.some(c => c.id === chapter.id)){
            selectedChapters.value.push(chapter)
        }
    }
}

// チャプター選択欄のコースを選択した際(選択コースIDが変更された場合)
watch(selectedCourseId, (id) => {
  const course = props.courses.find(c => c.id === id)
  if (course) {
    // 選択コースを取得
    selectedCourses.value = [course]
  }else{
    // コース一覧を取得
    selectedCourses.value = props.courses
  }
})

// チャプター選択時
const handleSelectChapter = (chapter: ChapterWithCourseName) => {
    // チャプター一覧が存在する際
    if(!selectedChapters.value.some(c => c.id === chapter.id)){
        // チャプター一覧にチャプター追加
        selectedChapters.value.push(chapter)
    }
}

// リストからチャプターを削除
const removeChapter = (id: number) => {
    selectedChapters.value = selectedChapters.value.filter(c => c.id !== id)
}

// 受講者登録処理
const storeParticipant = () => {
    form.chapters = selectedChapters // チャプター一覧
    form.post('/participants')
}

</script>

<template>
    <FormLayout title="受講者登録">
        <form
        @submit.prevent="storeParticipant"
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
            <div class="flex h-16 justify-between mb-4">
                <div class="relative ms-3 flex w-full">
                    <div>
                        <InputLabel :label="false"
                            class="text-sm leading-7 text-gray-600 mr-4"
                        >コース選択</InputLabel>
                        <!-- コース一覧がドロップダウンで表示され、選択したコース内チャプターを全てリストに追加 -->
                        <CourseDropdown :courses="courses" @selectCourse="handleSelectCourse"/>
                    </div>
                    <div class="relative flex ml-auto mr-4">
                        <div>
                            <InputLabel :label="false"
                                class="text-sm leading-7 text-gray-600 mr-4"
                            >コース内チャプター追加</InputLabel>
                            <!-- コース一覧をセレクト -->
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
                            <!-- 選択されたコース内のチャプターをドロップダウン表示され、選択したチャプターをリストに追加 -->
                            <ChapterDropdown :courses="selectedCourses" @selectChapter="handleSelectChapter"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <div class="relative">
                    <InputLabel
                        :label="false"
                        class="text-sm leading-7 text-gray-600"
                        value="受講カリキュラム"
                    />
                    <!-- 選択されたチャプターをリスト表示 -->
                    <ParticipantChapterList v-model="selectedChapters" @removeChapter="removeChapter"
                        class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200" />
                </div>
            </div>
            <div class="w-full p-2">
                <SubmitButton class="mx-auto mt-8">登録する</SubmitButton>
            </div>
        </form>
    </FormLayout>
</template>
