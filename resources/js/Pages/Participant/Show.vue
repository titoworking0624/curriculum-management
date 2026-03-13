<script setup lang="ts">
import CancelButton from '@/Components/CancelButton.vue';
import CopyButton from '@/Components/CopyButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import SubTitle from '@/Components/SubTitle.vue';
import TextInput from '@/Components/TextInput.vue';
import ChapterList from '@/Layouts/Chapter/ChapterList.vue';
import CurriculumList from '@/Layouts/Curriculum/CurriculumList.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import { Curriculum, Participant } from '@/types/course';
import { router, useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props =
// withDefaults(
    defineProps<{
        participant:Participant;
        curriculum:Curriculum | null;
        nextCurriculum:Curriculum | null;
        prevCurriculum:Curriculum | null;
    }>()
    // ,
    // {
    //     curriculum: () => ({
    //         id:0,
    //         name:"現在なし",
    //         curriculum_number:0,
    //         curriculum_code:"現在なし",
    //         content:"現在なし",
    //         checklist:"現在なし",
    //     }),
    // },
// )

// if (!props.curriculum) {
//   return
// }

// console.log(props.curriculum.name)

const completeCurriculum = () => {
    router.patch(route('complete',{participant:props.participant.id}), {}, {
        onSuccess: () => {
            router.get(route('participants.show', props.participant.id))
        }
    })
}
const stopCurriculum = () => {
    router.patch(route('stopCurriculum',{participant:props.participant.id}), {}, {
        onSuccess: () => {
            router.get(route('participants.show', props.participant.id))
        }
    })
}
const cancelCurriculum = () => {
    router.patch(route('cancelComplete',{participant:props.participant.id}), {}, {
        onSuccess: () => {
            router.get(route('participants.show', props.participant.id))
        }
    })
}
const startCurriculum = () => {
    router.patch(route('startCurriculum',{participant:props.participant.id}))
}

const title = props.participant.name + " - " + "現在の課題"
const subtitle = props.participant.name
</script>

<template>
    <FormLayout :title="title">
        <div class="flex flex-col -m-2">
          <div class="p-2">
            <div class="relative flex w-full">
                <SubTitle :value="subtitle" />
                <div class="w-1/2 mt-2 flex">
                    <SecondaryButton class="ml-auto" :href="route('participants.edit',{participant:props.participant.id})">編集する</SecondaryButton>
                </div>
            </div>
          </div>
          <template v-if="curriculum">
          <div class="p-2">
            <div class="relative">
                <InputLabel class="leading-7 text-sm text-gray-600" value="カリキュラムコード" />
                <div class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    <span v-if="curriculum">{{ curriculum.curriculum_code }}　</span>
                    <span v-else>カリキュラムなし</span>
                </div>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel class="leading-7 text-sm text-gray-600" value="カリキュラム名" />
                <div class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    <span v-if="curriculum">{{ curriculum.name }}　</span>
                    <span v-else>カリキュラムなし</span>
                </div>
            </div>
          </div>
          <div class="p-2 w-full">
            <div class="relative">
                <div class="flex h-10">
                    <InputLabel class="leading-7 text-sm text-gray-600 mt-auto" value="内容"/>
                    <CopyButton v-if="curriculum" :text="curriculum.content" class="ml-auto mt-1 mr-4">コピー</CopyButton>
                </div>
                <div class="h-32 w-full resize-none rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-6 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 whitespace-pre-line">
                    <span v-if="curriculum">{{ curriculum.content }}</span>
                    <span v-else>カリキュラムなし</span>
                    </div>
            </div>
          </div>
          <div class="p-2 w-full">
            <div class="relative">
                <InputLabel class="leading-7 text-sm text-gray-600" value="チェックリスト"/>
                <div class="h-32 w-full resize-none rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-6 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 whitespace-pre-line">
                    <span v-if="curriculum">{{ curriculum.checklist }}</span>
                    <span v-else>カリキュラムなし</span>
                </div>
            </div>
          </div>
        </template>
        <template v-else>
        <div class="flex flex-col text-center w-full my-6">
            <h2 class="mx-auto sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">課題未送信</h2>
            <p v-if="nextCurriculum" class="mx-auto sm:text-4xl text-3xl font-medium mt-2 text-gray-900">次回課題：{{ nextCurriculum.curriculum_code }}:{{ nextCurriculum.name }}</p>
        </div>


        </template>

          <div class="p-2 w-full">
            <div class="relative flex justify-between">
                <div v-if="curriculum" class="flex flex-col mr-auto">
                    <span>{{ curriculum.curriculum_code }}:{{ curriculum.name }}</span>
                    <CancelButton @click="stopCurriculum" class="mr-auto">課題停止</CancelButton>
                </div>
                <div v-else-if="prevCurriculum" class="flex flex-col mr-auto">
                    <span>{{ prevCurriculum.curriculum_code }}:{{ prevCurriculum.name }}</span>
                    <CancelButton @click="cancelCurriculum" class="mr-auto">提出完了キャンセル</CancelButton>
                </div>
                <div v-if="curriculum" class="flex flex-col ml-auto">
                    <!-- <span>現在の課題：{{ curriculum.curriculum_code }}:{{ curriculum.name }}</span> -->
                    <span v-if="nextCurriculum">次の課題：{{ nextCurriculum.curriculum_code }}:{{ nextCurriculum.name }}</span>
                    <span v-else>次の課題：未登録</span>
                    <PrimaryButton @click="completeCurriculum" class="ml-auto">提出完了</PrimaryButton>
                </div>
                <div v-else class="flex flex-col ml-auto">
                    <template v-if="nextCurriculum">
                        <span>{{ nextCurriculum.curriculum_code }}:{{ nextCurriculum.name }}</span>
                        <PrimaryButton  @click="startCurriculum" class="ml-auto">課題スタート</PrimaryButton>
                    </template>
                    <template v-else>
                        <span>カリキュラム未登録</span>
                    </template>
                </div>
            </div>
          </div>
        </div>


    </FormLayout>
</template>
