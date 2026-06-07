<script setup>
import { ref } from 'vue'
import PublicDefaultLayout from './layouts/PublicDefaultLayout.vue'
import { useHashRoute } from '@/composables/router'

import SplashScreen from './components/SplashScreen.vue'
import Home from './views/Home.vue'
import Trophees from './views/Trophees.vue'
import Label from './views/Label.vue'
import Information from './views/Information.vue'

const routes = [
  { hash: '#/home',         key: 'home',         component: Home },
  { hash: '#/trophee',      key: 'trophee',      component: Trophees },
  { hash: '#/label',        key: 'label',        component: Label },
  { hash: '#/informations', key: 'informations', component: Information },
  { hash: '#/prendre-rdv',  key: 'rdv',          component: Home, scrollTo: '#prendre-rdv-form' },
]

const { currentComponent, currentRoute } = useHashRoute(routes)

const splashVisible = ref(sessionStorage.getItem('splash_seen') !== 'true')
</script>

<template>
  <SplashScreen v-model:visible="splashVisible" />
  <PublicDefaultLayout v-if="!splashVisible" :current="currentRoute.key">
    <component :is="currentComponent" />
  </PublicDefaultLayout>
</template>
