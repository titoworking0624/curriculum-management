<!-- 受講者の課題(カリキュラム)一覧リスト -->
<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { NextCurriculum, ParticipantCurriculum } from '@/types/course';

const props = withDefaults(
    defineProps<{
        curricula:ParticipantCurriculum[],
        currentCurriculum?:ParticipantCurriculum | null
        prevCurriculum?:ParticipantCurriculum | null
        nextCurriculum?:NextCurriculum | null
        show?: boolean;
    }>(),
    {
        show: true,
    },
);

const current = props.curricula.filter(c => !c.completion_date)
const prev = props.curricula.filter(c => c.completion_date)
</script>

<template>
    <div class="p-2">
        <div class="relative">
            <template v-if="currentCurriculum">
            <InputLabel
                :label="false"
                class="text-sm leading-7 text-gray-600 py-1 px-1"
                value="現在の課題"
            />
            <table class="table-auto w-full text-left whitespace-no-wrap rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">コード</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カリキュラム名</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">確認</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :key="currentCurriculum.id">
                            <td class="px-4 py-3">{{currentCurriculum.curriculum.curriculum_code}}</td>
                            <td class="px-4 py-3">{{currentCurriculum.curriculum.name}}</td>
                            <!-- 開始 -->
                            <td class="px-4 py-3">{{currentCurriculum.starting_date}}</td>
                            <td class="px-4 py-3">
                                <PrimaryButton class="ml-auto mb-2" :href="route('participants.show',{participant:currentCurriculum.participant_chapter.participant_id})">確認</PrimaryButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>

            <InputLabel
            :label="false"
            class="text-sm leading-7 text-gray-600 py-1 px-1"
            value="次の課題"
            />
            <table class="table-auto w-full text-left whitespace-no-wrap rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200">
                <template v-if="nextCurriculum">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">チャプター名</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">コード</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カリキュラム名</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-3">{{nextCurriculum.chapter.name}}</td>
                            <td class="px-4 py-3">{{nextCurriculum.curriculum_code}}</td>
                            <td class="px-4 py-3">{{nextCurriculum.name}}</td>
                            <td class="px-4 py-3">
                                <PrimaryButton :href="route('curricula.show',{curriculum:nextCurriculum.id,chapter:nextCurriculum.chapter.id})">詳細</PrimaryButton>
                            </td>
                        </tr>
                    </tbody>
                </template>
                <template v-else>
                    <tr>
                        <td class="px-4 py-3">次回課題登録なし</td>
                    </tr>
                </template>
            </table>
            <template v-if="prevCurriculum && !currentCurriculum">
                <InputLabel
                    :label="false"
                    class="text-sm leading-7 text-gray-600 py-1 px-1"
                    value="完了した最新の課題"
                />
                <table class="table-auto w-full text-left whitespace-no-wrap rounded border border-gray-300 bg-gray-100 bg-opacity-50 px-3 py-1 text-base leading-8 text-gray-700 outline-none transition-colors duration-200 ease-in-out focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">コード</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カリキュラム名</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">完了日</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :key="prevCurriculum.id">
                                <td class="px-4 py-3">{{prevCurriculum.curriculum.curriculum_code}}</td>
                                <td class="px-4 py-3">{{prevCurriculum.curriculum.name}}</td>
                                <!-- 開始 -->
                                <td class="px-4 py-3">{{prevCurriculum.starting_date}}</td>
                                <td class="px-4 py-3">{{prevCurriculum.completion_date}}</td>
                            </tr>
                        </tbody>
                </table>
            </template>

        </div>
    </div>

</template>
