<!-- 受講者の登録チャプター一覧リスト -->
<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ChapterWithCourseName } from '@/types/course';
import draggableComponent from 'vuedraggable';

const props = defineProps<{
    fixedChapters?:ChapterWithCourseName[] | null
}>()

const emit = defineEmits<{
    (e:'removeChapter',value: number ):void
}>()

const removeChapter = (id:number) => {
    emit('removeChapter',id)
}

// チャプターリスト
const listChapters = defineModel<ChapterWithCourseName[]>()

</script>

<template>
    <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                <!-- <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">順番</th> -->
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">コース名</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">チャプター(章)名</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">完了日</th>
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
                    <td class="px-4 pr-0 py-3"></td>
                    <!-- <td class="px-4 py-3">{{index + 1}}</td> -->
                    <td class="px-4 py-3">{{chapter.courseName}}({{chapter.chapter_number}})</td>
                    <td class="px-4 py-3">{{chapter.name}}</td>
                    <td v-if="chapter.completion_date" class="px-4 py-3" colspan="2">{{chapter.completion_date}}</td>
                    <td v-else class="px-4 py-3 text-gray-400" colspan="2">現在のチャプター</td>
                    <!-- <td v-if="chapter.completion_date" class="px-4 py-3 text-gray-400">完</td> -->
                </tr>
            </template>
            <!-- 未開始入れ替え可能チャプターリスト -->
            <template #item="{element,index}">
                <tr>
                    <!-- 入れ替え可能タグ -->
                    <td class="cursor-move px-4 pr-0 py-3">☰</td>
                    <!-- 開始済みから数えた順番 -->
                    <!-- <td class="px-4 py-3">{{index + (fixedChapters?.length ?? 0) + 1}}</td> -->
                    <td class="px-4 py-3">{{element.courseName}}({{element.chapter_number}})</td>
                    <td class="px-4 py-3">{{element.name}}</td>
                    <td></td>
                    <!-- 未開始の場合削除ボタン -->
                    <td v-if="!element.isStarting" class="px-4 py-3">
                        <button @click="removeChapter(element.id)" class="text-red-500 hover:text-red-700">×</button>
                    </td>
                </tr>
            </template>
        </draggableComponent>
    </table>
</template>
