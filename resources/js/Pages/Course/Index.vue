<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ListLayout from '@/Layouts/ListLayout.vue';

defineProps({
    courses:Object
})

</script>

<template>
    <ListLayout title="コース一覧">
        <CreateButton :href="route('courses.create')">コース登録</CreateButton>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">詳細</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">コースコード</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">コース名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="c in courses">
                  <td class="px-4 py-3">
                      <PrimaryButton :href="route('courses.show',{course:c.id})">詳細</PrimaryButton>
                  </td>
                  <td class="px-4 py-3">{{c.course_code}}</td>
                  <td class="px-4 py-3">{{c.name}}</td>
                  <td class="px-4 py-3">
                      <SecondaryButton :href="route('courses.edit',{course:c})">編集</SecondaryButton>
                  </td>

              </tr>
          </tbody>
        </table>
    </ListLayout>
</template>
