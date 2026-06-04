<script setup>
const socials = [
  { label: 'Facebook',  icon: '/images/social/facebook.png',  href: 'https://www.facebook.com/hopitaux.universitaires.geneve' },
  { label: 'Instagram', icon: '/images/social/instagram.png', href: 'https://www.instagram.com/hug_ge/' },
  { label: 'X',         icon: '/images/social/x.png',         href: 'https://x.com/hug_ge?lang=fr' },
  { label: 'LinkedIn',  icon: '/images/social/linkedin.png',  href: 'https://ch.linkedin.com/company/hopitaux-universitaires-de-geneve?trk=ppro_cprof' },
]
const legal = [
  { label: 'Charte éthique', href: 'https://www.hug.ch/conditions_generales' },
  { label: 'Confidentialité', href: 'https://www.hug.ch/politique-protection-donnees-personnelles' },
]
</script>

<template>
  <footer class="w-full bg-beige-50">
    <div class="flex w-full flex-col items-start gap-3 px-4 py-3 text-left
                lg:flex-row lg:items-center lg:justify-between lg:gap-4 lg:px-[60px]">

      <div class="flex flex-col items-start gap-[22px] lg:flex-row lg:items-center lg:gap-3.5">
        <div class="flex gap-6 lg:gap-3.5">
          <a v-for="s in socials" :key="s.label" :href="s.href" :aria-label="s.label"
             class="transition-opacity hover:opacity-70">
            <img :src="s.icon" :alt="'Logo ' + s.label" class="h-[30px] w-[30px]" />
          </a>
        </div>
        <a href="https://www.hug.ch/bienvenue-aux-personnes-avec-deficit-ou-handicap" class="py-2.5 font-sans text-small text-violet-950 hover:text-violet-500 lg:py-0">
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
