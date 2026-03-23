<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { PageProps } from '@/types';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// const { auth } = defineProps<PageProps>()

const props = defineProps({
    auth:Object,
})

const showingNavigationDropdown = ref(false);
</script>

<template>
<header class="text-gray-600 body-font">
  <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
    <Link href="/index" class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
        <img :src="'/logo1.png'" alt="ロゴ">
    </Link>
    <nav class="md:mx-auto flex md:flex-row flex-col flex-wrap items-center text-base justify-center">
      <div class="md:mr-auto ml-4">
          <NavLink href="/index" class="mr-5 hover:text-gray-900">受講者一覧</NavLink>
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
    <div>
      <div class="bg-gray-100">
          <nav
              class="border-b border-gray-100 bg-white"
          >
              <!-- Primary Navigation Menu -->
              <div class="mx-auto px-4 sm:px-6 lg:px-8">
                  <div class="flex justify-between">

                      <div class="hidden sm:flex sm:items-center">
                          <!-- Settings Dropdown -->
                          <div class="relative ms-3">
                              <Dropdown align="right" width="48">
                                  <template #trigger>
                                      <span class="inline-flex rounded-md">
                                          <button
                                              type="button"
                                              class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                          >
                                              {{ $page.props.auth.user.name }}

                                              <svg
                                                  class="-me-0.5 ms-2 h-4 w-4"
                                                  xmlns="http://www.w3.org/2000/svg"
                                                  viewBox="0 0 20 20"
                                                  fill="currentColor"
                                              >
                                                  <path
                                                      fill-rule="evenodd"
                                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                      clip-rule="evenodd"
                                                  />
                                              </svg>
                                          </button>
                                      </span>
                                  </template>

                                  <template #content>
                                      <DropdownLink
                                          v-if="$page.props.auth.user.is_admin"
                                          :href="route('admin.users')"
                                      >
                                          スタッフ一覧
                                      </DropdownLink>
                                      <DropdownLink
                                          v-if="$page.props.auth.user.is_admin"
                                          :href="route('admin.users.create')"
                                      >
                                          スタッフ登録
                                      </DropdownLink>
                                      <DropdownLink
                                          v-if="!$page.props.auth.user.is_admin"
                                          :href="route('profile.edit')"
                                      >
                                          情報編集
                                      </DropdownLink>
                                      <DropdownLink
                                          :href="route('logout')"
                                          method="post"
                                          as="button"
                                      >
                                          ログアウト
                                      </DropdownLink>
                                  </template>
                              </Dropdown>
                          </div>
                      </div>

                      <!-- Hamburger -->
                      <div class="-me-2 flex items-center sm:hidden">
                          <button
                              @click="
                                  showingNavigationDropdown =
                                      !showingNavigationDropdown
                              "
                              class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                          >
                              <svg
                                  class="h-6 w-6"
                                  stroke="currentColor"
                                  fill="none"
                                  viewBox="0 0 24 24"
                              >
                                  <path
                                      :class="{
                                          hidden: showingNavigationDropdown,
                                          'inline-flex':
                                              !showingNavigationDropdown,
                                      }"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"
                                  />
                                  <path
                                      :class="{
                                          hidden: !showingNavigationDropdown,
                                          'inline-flex':
                                              showingNavigationDropdown,
                                      }"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"
                                  />
                              </svg>
                          </button>
                      </div>
                  </div>
              </div>

              <!-- Responsive Navigation Menu -->
              <div
                  :class="{
                      block: showingNavigationDropdown,
                      hidden: !showingNavigationDropdown,
                  }"
                  class="sm:hidden"
              >
                  <!-- <div class="space-y-1 pb-3 pt-2">
                      <ResponsiveNavLink
                          :href="route('dashboard')"
                          :active="route().current('dashboard')"
                      >
                          Dashboard
                      </ResponsiveNavLink>
                  </div> -->

                  <!-- Responsive Settings Options -->
                  <div
                      class="border-t border-gray-200 pb-1 pt-4"
                  >
                      <div class="px-4">
                          <div
                              class="text-base font-medium text-gray-800"
                          >
                              {{ $page.props.auth.user.name }}
                          </div>
                          <div class="text-sm font-medium text-gray-500">
                              {{ $page.props.auth.user.email }}
                          </div>
                      </div>

                      <div class="mt-3 space-y-1">
                          <ResponsiveNavLink
                              :href="route('logout')"
                              method="post"
                              as="button"
                          >
                              Log Out
                          </ResponsiveNavLink>
                      </div>
                  </div>
              </div>
          </nav>

          <!-- Page Heading -->
          <header
              class="bg-white shadow"
              v-if="$slots.header"
          >
              <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                  <slot name="header" />
              </div>
          </header>

          <!-- Page Content -->
          <main>
              <slot />
          </main>
      </div>
    </div>
</div>
</header>
</template>
