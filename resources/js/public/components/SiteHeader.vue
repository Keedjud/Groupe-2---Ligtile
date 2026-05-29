<script setup>
import { useNavigation } from '@/composables/useNavigation'
import { useDisclosure } from '@/composables/useDisclosure'

const props = defineProps({
  current: { type: String, default: null },
  logo:    { type: String, default: '/images/logo-hug.png' },
})

const { links, isActive } = useNavigation(() => props.current)
const { isOpen, toggle, close } = useDisclosure()
</script>

<template>
  <header class="sticky top-0 z-30 w-full bg-beige-50 shadow-[0_4px_4px_rgba(0,0,0,0.10)]">
    <div class="mx-auto flex h-[55px] max-w-[1512px] items-center justify-between px-3 py-1.5 lg:px-[60px]">

      <a href="/" class="shrink-0">
        <img :src="logo" alt="HUG — Hôpitaux Universitaires de Genève" class="h-7 w-auto" />
      </a>

      <nav class="hidden items-center gap-7 lg:flex">
        <a v-for="link in links" :key="link.key" :href="link.href"
           class="font-sans text-regular transition-colors hover:text-violet-500"
           :class="isActive(link.key) ? 'font-bold text-violet-900' : 'text-violet-950'">
          {{ link.label }}
        </a>
      </nav>

      <button type="button" @click="toggle"
              class="flex h-10 w-10 items-center justify-center rounded-full text-violet-950 lg:hidden"
              aria-label="Menu" :aria-expanded="isOpen">
        <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-7 w-7"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
        </svg>
      </button>
    </div>

    <Transition
      enter-active-class="transition duration-150 ease-out origin-top"
      enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-100 ease-in origin-top"
      leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
      <nav v-show="isOpen" class="border-t border-black/10 bg-beige-50 px-6 pb-4 pt-2 lg:hidden">
        <a v-for="link in links" :key="link.key" :href="link.href" @click="close"
           class="block border-b border-black/5 py-3 font-sans text-h5 text-violet-950 last:border-0 hover:text-violet-500">
          {{ link.label }}
        </a>
      </nav>
    </Transition>
  </header>
</template>
