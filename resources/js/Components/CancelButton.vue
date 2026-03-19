<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <Link as="button"
        class="inline-flex items-center rounded-md border border-transparent bg-red-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-700 focus:bg-red-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-red-900"
    >
        <slot />
    </Link>
</template>
