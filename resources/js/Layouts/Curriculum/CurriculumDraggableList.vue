<script setup lang="ts">
import CreateButton from '@/Components/CreateButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HeadLayout from '@/Layouts/HeadLayout.vue';
import ListLayout from '@/Layouts/ListLayout.vue';
import { Curriculum } from '@/types/course';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import draggableComponent from 'vuedraggable';

const props = withDefaults(
    defineProps<{
        // curricula:Curriculum[],
        id?: number,
    }>(),
    {
        id:0,
    },
);
const listCurricula = defineModel<Curriculum[]>()

// const emit = defineEmits<{
//   (e:"updateOrder", value:Curriculum[]):void
// }>()

// const handleDragEnd = () => {
//   emit("updateOrder", listCurricula.value)
// }
</script>

<template>
        <table class="table-auto w-full text-left whitespace-no-wrap">
          <thead>
            <tr>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">入替</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">順番</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">課題番号</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カリキュラム名</th>
              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">詳細</th>
            </tr>
          </thead>
          <draggableComponent v-model="listCurricula" tag="tbody" itemKey="id" handle=".cursor-move" animation="200"
    ghost-class="opacity-40"
    chosen-class="bg-yellow-100"

    :force-fallback="true"
    :fallback-on-body="true"
    :delay="50"
    :delay-on-touch-only="true">
              <template #item="{element,index}">
                  <tr :class="{'bg-yellow-400':element.id === props.id}">
                      <td class="cursor-move px-4 py-3">☰</td>
                      <td class="px-4 py-3">{{index + 1}}</td>
                      <td class="px-4 py-3">{{element.curriculum_code}}</td>
                      <td class="px-4 py-3">{{element.name}}</td>
                      <td class="px-4 py-3">
                        <PrimaryButton :href="route('curricula.show',{curriculum:element.id})">詳細</PrimaryButton>
                      </td>

                  </tr>
              </template>
          </draggableComponent>
        </table>

</template>
