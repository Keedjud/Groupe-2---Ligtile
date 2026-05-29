<script setup>
defineProps({
  url: { type: String, default: '#' },
})
</script>

<template>
  <div class="flex w-full items-center justify-between gap-3 bg-rouge-500 px-3 py-0 text-left font-sans text-h5 font-bold text-light-palette-black lg:block lg:py-2.5 lg:text-center lg:text-h4">
    <span class="block lg:inline">Ceci est un site fictif.</span>
    <a :href="url" class="shrink-0 text-small font-normal underline underline-offset-2 lg:ml-4 lg:inline lg:text-h5">
      Plus d'informations
    </a>
  </div>
</template>
