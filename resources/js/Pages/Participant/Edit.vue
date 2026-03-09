<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ChapterDropdown from '@/Components/Participant/ChapterDropdown.vue';
import CourseDropdown from '@/Components/Participant/CourseDropdown.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import CurriculumList from '@/Layouts/Curriculum/CurriculumList.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import ParticipantChapter from '@/Layouts/Participant/ParticipantChapter.vue';
import ParticipantCurricula from '@/Layouts/Participant/ParticipantCurricula.vue';
import { Chapter, ChapterWithCourseName, Course, Curriculum, CurriculumWithCourseName, Participant, ParticipantChapter as PC} from '@/types/course';
import { useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    courses: Course[];
    participant:Participant,
    participantChapters: CurriculumWithCourseName[]
    errors: Object;
}>();

const form = useForm({
    chapters:[] as number[],
    name: props.participant.name,
});
const initialChapterIds = new Set(
  props.participantChapters.map(pc => pc.chapter.id)
)
const chapterIds = props.participantChapters.map(pc => pc.chapter.id)

form.chapters = [...new Set(chapterIds)]

const allChapters = computed<ChapterWithCourseName[]>(() =>
  props.courses.flatMap(course =>
    course.chapters.map(ch => ({
      ...ch,
      courseName: course.name,
      isStarting: initialChapterIds.has(ch.id)
    }))
  )
)

const selectedChapters = computed(() =>
  allChapters.value.filter(ch => form.chapters.includes(ch.id))
)

const removeChapter = (chapterId:number) => {
  form.chapters = form.chapters.filter(id => id !== chapterId)
}

const selectedCurricula = ref<CurriculumWithCourseName[]>(props.participantChapters)

// const addCourses = props.courses.find(c => c.id === designCourseId)

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
            form.chapters.push(chapter.id)
        }
    }
}

const selectedCourses = ref<Course[]>([...props.courses])

const selectedCourseId = ref<number | null>(null)

watch(selectedCourseId, (id) => {
  const course = props.courses.find(c => c.id === id)
  if (course) {
    selectedCourses.value = [course]
  }else{
    selectedCourses.value = props.courses
  }
})
// watch(selectedChapters, (ch) => {
//   form.chapters.push(ch.id)
// })

const handleSelectChapter = (chapter: ChapterWithCourseName) => {

    if(!selectedChapters.value.some(c => c.id === chapter.id)){
        selectedChapters.value.push(chapter)
        form.chapters.push(chapter.id)
    }
}
// const removeChapter = (id: number) => {
//     selectedChapters.value = selectedChapters.value.filter(c => c.id !== id)
// }
const storeParticipant = () => {
    // form.chapters = selectedChapters
    form.put(route('participants.update',{participant:props.participant.id}))
}

</script>

<template>
    <FormLayout title="受講者編集">
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
        <div class="p-2">
            <div class="relative">
                <InputLabel
                    :label="false"
                    class="text-sm leading-7 text-gray-600"
                    value="受講カリキュラム"
                />
                <ParticipantCurricula :curricula="selectedCurricula" @removeChapter="removeChapter"
                    class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200" />
            </div>
        </div>
            <div class="flex h-16 justify-between mb-4">
                <div class="relative ms-3 flex w-full">
                    <div>
                        <InputLabel :label="false"
                            class="text-sm leading-7 text-gray-600 mr-4"
                        >コース選択</InputLabel>
                        <CourseDropdown :courses="courses" @selectCourse="handleSelectCourse"/>
                    </div>
                    <div class="relative flex ml-auto mr-4">
                        <div>
                            <InputLabel :label="false"
                                class="text-sm leading-7 text-gray-600 mr-4"
                            >コース選択</InputLabel>
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
                        value="受講チャプター"
                    />
                    <ParticipantChapter :chapters="selectedChapters" @removeChapter="removeChapter"
                        class="w-full rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200" />
                </div>
            </div>
            <div class="w-full p-2">
                <SubmitButton class="mx-auto mt-8">更新する</SubmitButton>
            </div>
        </form>
    </FormLayout>
</template>
