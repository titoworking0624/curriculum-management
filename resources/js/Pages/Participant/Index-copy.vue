<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    participants:Object
})

</script>

<template>
    <Head title="受講者一覧" />
    <HeadLayout />

<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-col text-center w-full mb-20">
      <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">受講者一覧</h1>
    </div>
    <div class="lg:w-3/4 w-full mx-auto overflow-auto">
      <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
          <tr>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">提出確認</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">受講者</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題番号</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題名</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題送信日</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
          </tr>
        </thead>
        <tbody>
            <tr v-for="p in participants">
                <td class="px-4 py-3"><PrimaryButton>確認</PrimaryButton></td>
                <td class="px-4 py-3">{{p.pa_name}}</td>
                <td class="px-4 py-3">{{p.cu_code}}</td>
                <td class="px-4 py-3">{{p.cu_name}}</td>
                <td class="px-4 py-3">{{p.st_date}}</td>
                <td class="px-4 py-3"><SecondaryButton>編集</SecondaryButton></td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>
</template>
