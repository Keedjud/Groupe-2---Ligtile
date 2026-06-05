<script setup>
defineProps({
  current: { type: String, default: null },
  logo:    { type: String, default: '/images/logos/logo-hug.png' },
})

const links = [
  { key: 'inscription', label: 'Formulaire', href: '#/inscription' },
  { key: 'prevention',  label: 'Prévention', href: '#/prevention' },
]
</script>

<template>
  <header class="relative w-full bg-form-bg shadow-[0_4px_4px_rgba(0,0,0,0.10)]">
    <div class="flex h-[55px] w-full items-center justify-between px-[60px] py-1.5">

      <div class="flex items-center gap-[22px]">
        <a href="#/accueil" class="shrink-0">
          <img :src="logo" alt="logo HUG" class="h-7 w-auto" />
        </a>
        <span class="h-[35px] w-px bg-gris-50" aria-hidden="true"></span>
        <!-- Logo entreprise co-brandée (placeholder Migros). -->
        <span class="text-h4 font-bold tracking-wide text-light-palette-orange">MIGROS</span>
      </div>

      <nav class="flex items-center gap-6">
        <a v-for="link in links" :key="link.key" :href="link.href"
           class="font-sans text-regular transition-colors hover:text-violet-500"
           :class="current === link.key ? 'font-bold text-violet-900' : 'text-texte-primary-dark'">
          {{ link.label }}
        </a>
      </nav>
    </div>
  </header>
</template>
