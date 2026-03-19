<script setup lang="ts">
withDefaults(
    defineProps<{
        value?: string | "";
    }>(),
    {
        value: '一覧',
    },
);
</script>

<template>
    <h2 class="font-medium title-font text-2xl w-full mt-2 pl-4 text-gray-900">{{ value }}</h2>
</template>
