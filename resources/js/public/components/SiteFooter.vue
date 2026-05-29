<script setup>
const socials = [
  { label: 'Facebook',  icon: '/images/facebook.png',  href: '#' },
  { label: 'Instagram', icon: '/images/instagram.png', href: '#' },
  { label: 'X',         icon: '/images/x.png',         href: '#' },
  { label: 'LinkedIn',  icon: '/images/linkedln.png',  href: '#' },
]
const legal = [
  { label: 'Charte éthique', href: '#' },
  { label: 'Politique de confidentialité', href: '#' },
  { label: 'Protection des données', href: '#' },
]
</script>

<template>
  <footer class="w-full bg-beige-50">
    <div class="mx-auto flex max-w-[1512px] flex-col items-start gap-3 p-3 text-left
                lg:flex-row lg:items-center lg:justify-between lg:gap-4 lg:px-[60px]">

      <div class="flex flex-col items-start gap-[22px] lg:flex-row lg:items-center lg:gap-3.5">
        <div class="flex gap-6 lg:gap-3.5">
          <a v-for="s in socials" :key="s.label" :href="s.href" :aria-label="s.label"
             class="transition-opacity hover:opacity-70">
            <img :src="s.icon" :alt="s.label" class="h-[30px] w-[30px]" />
          </a>
        </div>
        <a href="#" class="py-2.5 font-sans text-small text-violet-950 hover:text-violet-500 lg:py-0">
          Accessibilité
        </a>
      </div>

      <nav class="flex flex-col items-start gap-3 font-sans text-small text-violet-950
                  lg:flex-row lg:items-center lg:gap-x-6 lg:gap-y-2">
        <a v-for="l in legal" :key="l.label" :href="l.href"
           class="py-2.5 hover:text-violet-500 lg:py-0">
          {{ l.label }}
        </a>
      </nav>
    </div>
  </footer>
</template>
