<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ListLayout from '@/Layouts/ListLayout.vue';

const props = defineProps({
    users:Object,
})

</script>

<template>
    <ListLayout title="スタッフ一覧">
    <CreateButton :href="route('admin.users.create')">スタッフ登録</CreateButton>

        <!-- <div class="flex flex-col text-center w-full my-6">
            <h2 class="mx-auto sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">送信済み</h2>
        </div> -->
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
              <!-- <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">提出確認</th> -->
              <!-- <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">受講者</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題番号</th> -->
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">スタッフ名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メールアドレス</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">スタッフ登録日</th>
              <!-- <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th> -->
            </tr>
          </thead>
          <tbody>
              <tr v-for="p in props.users" :key="p.id">
                  <!-- <td class="px-4 py-3">
                      <PrimaryButton :href="route('participants.show',{participant:p.pa_id})">確認</PrimaryButton></td> -->
                      <td class="px-4 py-3">{{p.name}}</td>
                      <td class="px-4 py-3">{{p.email}}</td>
                      <td class="px-4 py-3">{{p.created_at}}</td>
                      <!-- <td class="px-4 py-3"><SecondaryButton :href="route('participants.edit',{participant:p.pa_id})">編集</SecondaryButton></td> -->
              </tr>
          </tbody>
        </table>
    </ListLayout>

</template>
