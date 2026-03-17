<script setup lang="ts">
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
<header class="text-gray-600 body-font">
  <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
    <Link href="/" class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
        <img :src="'/logo1.png'" alt="ロゴ">
    </Link>
    <nav class="md:mx-auto flex md:flex-row flex-col flex-wrap items-center text-base justify-center">
      <div class="md:mr-auto ml-4">
          <NavLink href="/" class="mr-5 hover:text-gray-900">受講者一覧</NavLink>
          <NavLink href="/courses" class="mr-5 hover:text-gray-900">コース一覧</NavLink>
      </div>
        <div class="md:mr-auto grid grid-cols-2">
            <Link as="button" :href="route('participants.create')" class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mx-2 mt-4 md:mt-0">受講者登録
            </Link>
            <Link as="button" :href="route('courses.create')" class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mx-2 mt-4 md:mt-0">コース作成
            </Link>
            <Link as="button" :href="route('chapters.create')" class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded mx-2 text-base mt-1">チャプター作成
            </Link>
            <Link as="button" :href="route('curricula.create')" class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded mx-2 text-base mt-1">カリキュラム作成
            </Link>
        </div>
    </nav>
  </div>
</header>
</template>
