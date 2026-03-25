<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    participants:Object,
    starting:Object,
    stoped:Object,
    notRegistered:Object,
})

const today = new Date()

// 今日から何日経過しているか
const diffDays = (dateStr: string) => {
  const start = new Date(dateStr)

  const diff = today.getTime() - start.getTime()
  return Math.floor(diff / (1000 * 60 * 60 * 24))
}

</script>

<template>
    <ListLayout title="受講者一覧">
    <CreateButton :href="route('participants.create')">受講者登録</CreateButton>

        <div class="flex flex-col text-center w-full my-6">
            <h2 class="mx-auto sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">送信済み</h2>
        </div>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">提出確認</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">受講者</th>
              <!-- <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題番号</th> -->
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題送信日</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">経過</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="p in starting" :key="p.id">
                  <td class="px-4 py-3">
                      <PrimaryButton :href="route('participants.show',{participant:p.pa_id})">確認</PrimaryButton></td>
                      <td class="px-4 py-3">{{p.pa_name}}</td>
                      <!-- <td class="px-4 py-3">{{p.cu_code}}</td> -->
                      <td class="px-4 py-3">{{p.cu_name}}</td>
                      <td class="px-4 py-3">{{p.st_date}}</td>
                      <td class="px-4 py-3"
                          :class="{'text-red-700 bg-yellow-300':diffDays(p.st_date) >= 30}">
                          {{diffDays(p.st_date)}}日
                        </td>
                      <td class="px-4 py-3"><SecondaryButton :href="route('participants.edit',{participant:p.pa_id})">編集</SecondaryButton></td>
              </tr>
          </tbody>
        </table>
        <div class="flex flex-col text-center w-full my-6">
            <h2 class="mx-auto sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">未送信</h2>
        </div>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">課題送信</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">受講者</th>
              <!-- <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題番号</th> -->
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題完了日</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">経過</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="p in participants" :key="p.id">
                <template v-if="p.co_date">
                    <td class="px-4 py-3">
                      <PrimaryButton :href="route('participants.show',{participant:p.pa_id})">確認</PrimaryButton></td>
                      <td class="px-4 py-3">{{p.pa_name}}</td>
                      <!-- <td class="px-4 py-3">{{p.cu_code}}</td> -->
                      <td class="px-4 py-3">{{p.cu_name}}</td>
                      <td class="px-4 py-3">{{p.co_date}}</td>
                      <td class="px-4 py-3"
                          :class="{'text-red-700 bg-yellow-300':diffDays(p.co_date) >= 30}">
                          {{diffDays(p.co_date)}}日
                      </td>
                      <td class="px-4 py-3"><SecondaryButton :href="route('participants.edit',{participant:p.pa_id})">編集</SecondaryButton></td>
                </template>
              </tr>
          </tbody>
        </table>
        <div class="flex flex-col text-center w-full my-6">
            <h2 class="mx-auto sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">カリキュラム未開始</h2>
        </div>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">課題スタート</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">受講者</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">登録チャプター名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="p in participants" :key="p.id">
                <template v-if="!p.co_date && !p.st_date">
                    <td class="px-4 py-3">
                      <PrimaryButton :href="route('participants.show',{participant:p.pa_id})">確認</PrimaryButton></td>
                      <td class="px-4 py-3">{{p.pa_name}}</td>
                      <td v-if="p.ch_name" class="px-4 py-3">{{p.ch_name}}</td>
                      <td v-else class="px-4 py-3">カリキュラム未登録</td>
                      <td class="px-4 py-3"><SecondaryButton :href="route('participants.edit',{participant:p.pa_id})">編集</SecondaryButton></td>
                </template>
              </tr>
          </tbody>
        </table>
    </ListLayout>

</template>
