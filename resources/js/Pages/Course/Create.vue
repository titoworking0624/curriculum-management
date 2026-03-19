<!-- コース作成画面 -->
<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import TextInput from '@/Components/TextInput.vue';
import FormLayout from '@/Layouts/FormLayout].vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    errors:Object,
})

const form = useForm({
    code:null,
    name:null,
})
</script>

<template>
    <FormLayout title="コース作成">
        <form @submit.prevent="form.post('/courses')" class="flex flex-col -m-2">
          <div class="p-2">
            <div class="relative">
                <InputLabel for="code" class="leading-7 text-sm text-gray-600" value="コースコード(英字2文字)" />
                <input type="text" id="code" name="code" v-model="form.code" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.code"/>
            </div>
          </div>
          <div class="p-2">
            <div class="relative">
                <InputLabel for="name" class="leading-7 text-sm text-gray-600" value="コース名" />
                <input type="text" id="name" name="name" v-model="form.name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                <InputError :message="form.errors.name"/>
            </div>
          </div>
          <div class="p-2 w-full">
            <SubmitButton class="mx-auto mt-8">登録する</SubmitButton>
          </div>
        </form>
    </FormLayout>
</template>
