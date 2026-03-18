<script setup lang="ts">
withDefaults(
    defineProps<{
    value?: string;
    label?:boolean
    }>(),
    {
        label:true
    }
);
</script>

<template>
    <label v-if="label" class="block text-sm font-medium text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
    <div v-else class="block text-sm font-medium text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </div>
</template>
