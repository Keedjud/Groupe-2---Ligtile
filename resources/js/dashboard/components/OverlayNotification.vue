<script setup>
import { useOverlay } from '../composables/useOverlay'

const { message, visible, hide } = useOverlay()
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-2"
        >
            <div
                v-if="visible"
                class="fixed bottom-20 left-1/2 z-50 -translate-x-1/2 flex items-center gap-3 rounded-full bg-vert-600 px-6 py-3 font-sans text-regular font-medium text-white shadow-lg"
            >
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <span>{{ message }}</span>
                <button type="button" @click="hide" aria-label="Fermer" class="ml-1 opacity-70 hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </Transition>
    </Teleport>
</template>
