<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import TextInput from '@/Components/TextInput.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    chapters:Object,
    chapter:Object,
    errors:Object,
})

const form = useForm({
    chapter_id:props.chapter?.id ??null,
    curriculum_number:null,
    name:null,
    content:null,
    checklist:null,
})
</script>

<template>
    <FormLayout title="カリキュラム作成">
        <form @submit.prevent="form.post('/chapters')" class="flex flex-col -m-2">
          <div class="p-2">
            <div class="relative">
                <InputLabel for="chapter" class="leading-7 text-sm text-gray-600" value="使用章" />
                <select name="chapter" id="chapter" v-model="form.chapter_id">
                    <option v-for="c in chapters" :key="c.id" :value="c.id" :selected="c.id === form.chapter_id">{{ c.chapter_number }}：{{ c.name }}</option>
                </select>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="curriculum_number" class="leading-7 text-sm text-gray-600" value="カリキュラム順" />
                <input type="number" id="curriculum_number" name="curriculum_number" v-model="form.curriculum_number" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.curriculum_number"/>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="name" class="leading-7 text-sm text-gray-600" value="カリキュラム名" />
                <input type="text" id="name" name="name" v-model="form.name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.name"/>
            </div>
          </div>
          <div class="p-2 w-full">
            <div class="relative">
                <InputLabel for="content" class="leading-7 text-sm text-gray-600" value="内容" />
                <textarea id="content" name="content" v-model="form.content" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                <InputError :message="form.errors.content"/>
            </div>
          </div>
          <div class="p-2 w-full">
            <div class="relative">
                <InputLabel for="checklist" class="leading-7 text-sm text-gray-600" value="チェックリスト" />
                <textarea id="checklist" name="checklist" v-model="form.checklist" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                <InputError :message="form.errors.checklist"/>
            </div>
          </div>
          <div class="p-2 w-full">
            <SubmitButton class="mx-auto mt-8">登録する</SubmitButton>
          </div>
        </form>
    </FormLayout>
</template>
