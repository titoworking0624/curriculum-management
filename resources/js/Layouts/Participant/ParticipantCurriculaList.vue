<!-- 受講者の課題(カリキュラム)一覧リスト -->
<script setup lang="ts">
import { ParticipantCurriculum } from '@/types/course';

withDefaults(
    defineProps<{
        curricula:ParticipantCurriculum[],
        show?: boolean;
    }>(),
    {
        show: true,
    },
);

</script>

<template>
    <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
            <tr>
                <th v-if="show" class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">コード</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カリキュラム名</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">完了日</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="c in curricula" :key="c.curriculum.id" :class="{'bg-yellow-300':!c.completion_date}">
                <td class="px-4 py-3">{{c.curriculum.curriculum_code}}</td>
                <td class="px-4 py-3">{{c.curriculum.name}}</td>
                <!-- 開始 -->
                <td v-if="c.starting_date" class="px-4 py-3">{{c.starting_date}}</td>
                <td v-else class="px-4 py-3 text-gray-400">開始前</td>
                <!-- 完了 -->
                <td v-if="c.completion_date" class="px-4 py-3">{{c.completion_date}}</td>
                <td v-else class="px-4 py-3 text-gray-400">未完了</td>
            </tr>
        </tbody>
    </table>
</template>
