<!-- Sélecteur de couleur stylé (input[type=color] habillé) -->
<script setup>
const props = defineProps({
  label:     { type: String, required: true },
  modelValue:{ type: String, default: '#681764' },
})
const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <div class="flex flex-col items-start gap-1">
    <span class="font-sans text-regular">{{ label }}</span>
    <!-- Carré couleur cliquable qui ouvre le color picker natif -->
    <label class="cursor-pointer">
      <div
        class="h-[33px] w-[33px] rounded-lg border border-violet-200 p-1 shadow-[0_0_4px_rgba(0,0,0,0.15)]"
        :style="{ backgroundColor: modelValue }"
      />
      <input
        type="color"
        :value="modelValue"
        @input="emit('update:modelValue', $event.target.value)"
        class="sr-only"
      />
    </label>
  </div>
</template>
