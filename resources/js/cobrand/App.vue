<script setup>
import CobrandHeader from './components/CobrandHeader.vue'
import { useHashRoute } from '@/composables/router'

import Accueil from './views/Accueil.vue'
import Prevention from './views/Prevention.vue'
import Quiz from './views/Quiz.vue'
import Redirect from './views/Redirect.vue'

const routes = [
  { hash: '#/accueil',     key: 'accueil',     component: Accueil },
  { hash: '#/prevention',  key: 'prevention',  component: Prevention },
  { hash: '#/quiz',        key: 'quiz',        component: Quiz },
  { hash: '#/inscription', key: 'inscription', component: Redirect },
]

const { currentComponent, currentRoute } = useHashRoute(routes)
</script>

<template>
  <div class="min-h-screen bg-beige-50 text-violet-900">
    <CobrandHeader :current="currentRoute.key" />
    <main class="mx-auto max-w-[1200px] px-6 py-10 lg:px-10">
      <component :is="currentComponent" />
    </main>
  </div>
</template>
