<script setup>
import FictifBanner from '../components/FictifBanner.vue'
import SiteHeader   from '../components/SiteHeader.vue'
import SiteFooter   from '../components/SiteFooter.vue'
import FictifNotice from '../components/FictifNotice.vue'

defineProps({
  current: { type: String, default: null },
})
</script>

<template>
  <div class="flex min-h-screen flex-col bg-beige-50 font-sans text-light-primary antialiased">
    <FictifBanner url="/a-propos" />
    <SiteHeader :current="current" />

    <main class="flex-1">
      <slot />
    </main>

    <SiteFooter />
    <FictifNotice />
  </div>
</template>
