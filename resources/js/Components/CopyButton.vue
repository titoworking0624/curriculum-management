<script setup lang="ts">
defineProps<{
    text: string
}>()


const copyToClipboard = async (text: string) => {
  try {
    await navigator.clipboard.writeText(text)
    // alert('コピーしました')
  } catch (error) {
    console.error('コピー失敗', error)
  }
}
</script>

<template>
    <button @click="copyToClipboard(text)" class="group relative inline-flex h-7 items-center justify-center overflow-hidden rounded-md border border-neutral-200 bg-transparent px-4 font-medium text-neutral-600 transition-all duration-100 [box-shadow:3px_3px_rgb(82_82_82)] active:translate-x-[3px] active:translate-y-[3px] active:[box-shadow:0px_0px_rgb(82_82_82)]">コピー</button>
</template>
