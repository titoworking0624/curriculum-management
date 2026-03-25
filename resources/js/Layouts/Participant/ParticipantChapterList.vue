<!-- 受講者の登録チャプター一覧リスト -->
<script setup lang="ts">
import CancelButton from '@/Components/CancelButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ChapterWithCourseName } from '@/types/course';
import { VueElement } from 'vue';
import draggableComponent from 'vuedraggable';

const props = defineProps<{
    fixedChapters?:ChapterWithCourseName[] | null
}>()

const emit = defineEmits<{
    (e:'removeChapter',value: number ):void
    (e:'endChapter',value: number ):void
    (e:'cancelEndChapter',value: number ):void
}>()

const removeChapter = (id:number) => {
    emit('removeChapter',id)
}
const endChapter = (id:number) => {
    emit('endChapter',id)
}

const cancelEndChapter = (id:number) => {
    emit('cancelEndChapter',id)
}

// チャプターリスト
const listChapters = defineModel<ChapterWithCourseName[]>()

</script>

<template>
    <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
            <tr>
                <th class="md:px-4 md:py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                <!-- <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">順番</th> -->
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">コース名</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">チャプター(章)名</th>
                <th class="md:px-4 md:py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">詳細</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"><span class="text-gray-400">開始</span>/完了日</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">削除</th>
            </tr>
        </thead>

        <draggableComponent v-model="listChapters" tag="tbody" itemKey="id" handle=".cursor-move" animation="200"
            ghost-class="opacity-40"
            chosen-class="bg-yellow-100"

            :force-fallback="true"
            :fallback-on-body="true"
            :delay="50"
            :delay-on-touch-only="true"
        >
            <!-- 開始済みチャプターリスト -->
            <template #header v-if="fixedChapters">
                <tr v-for="(chapter,index) in props.fixedChapters" :class="{'bg-yellow-300':!chapter.completion_date}"
                    :key="chapter.id"
                    class="bg-gray-100"
                >
                    <td class="px-4 md:py-3 pr-0"></td>
                    <!-- <td class="px-4 py-3">{{index + 1}}</td> -->
                    <td class="px-4 py-3">{{chapter.courseName}}({{chapter.chapter_number}})</td>
                    <td class="px-4 py-3">{{chapter.name}}</td>
                    <td class="md:px-4 md:py-3 w-16">
                        <!-- 2026-03-18 ポップアップにしたい -->
                        <PrimaryButton class="w-16" :href="route('chapters.show',{chapter:chapter.id})">詳細</PrimaryButton>
                    </td>
                    <template v-if="chapter.completion_date">
                        <td class="px-4 py-3">{{chapter.completion_date}}</td>
                        <td class="px-4 py-3">
                            <CancelButton @click="cancelEndChapter(chapter.id)">完了取消</CancelButton>
                        </td>
                    </template>
                    <template v-else>
                        <td class="px-4 py-3 text-gray-400">{{chapter.starting_date}}</td>
                        <td class="px-4 py-3">
                            <div class="flex">
                                <CancelButton class="ml-auto" @click="endChapter(chapter.id)">強制完了</CancelButton>
                            </div>
                        </td>
                    </template>
                    <!-- <td v-if="chapter.completion_date" class="px-4 py-3 text-gray-400">完</td> -->
                </tr>
            </template>
            <!-- 未開始入れ替え可能チャプターリスト -->
            <template #item="{element,index}">
                <tr :key="element.id">
                    <!-- 入れ替え可能タグ -->
                    <td class="cursor-move px-4 md:py-3 pr-0">☰</td>
                    <!-- 開始済みから数えた順番 -->
                    <!-- <td class="px-4 py-3">{{index + (fixedChapters?.length ?? 0) + 1}}</td> -->
                    <td class="px-4 py-3">{{element.courseName}}({{element.chapter_number}})</td>
                    <td class="px-4 py-3">{{element.name}}</td>
                    <td class="md:px-4 md:py-3 w-16">
                        <!-- 2026-03-18 ポップアップにしたい -->
                        <PrimaryButton :href="route('chapters.show',{chapter:element.id})">詳細</PrimaryButton>
                    </td>
                    <td class="px-4 py-3"></td>
                    <!-- 未開始の場合削除ボタン -->
                    <td v-if="!element.isStarting" class="px-4 py-3">
                        <button @click="removeChapter(element.id)" class="text-red-500 hover:text-red-700">×</button>
                    </td>
                </tr>
            </template>
        </draggableComponent>
    </table>
</template>
