<script setup>
import PublicDefaultLayout from './layouts/PublicDefaultLayout.vue'
import { useHashRoute } from '@/composables/router'

import SplashScreen from './components/SplashScreen.vue'
import Home from './views/Home.vue'
import Trophees from './views/Trophees.vue'
import Label from './views/Label.vue'
import Information from './views/Information.vue'
import Contact from './views/Contact.vue'

const routes = [
  { hash: '#/home',         key: 'home',         component: Home },
  { hash: '#/trophee',      key: 'trophee',      component: Trophees },
  { hash: '#/label',        key: 'label',        component: Label },
  { hash: '#/informations', key: 'informations', component: Information },
  { hash: '#/contact',      key: 'contact',      component: Contact },
  { hash: '#/prendre-rdv',  key: 'rdv',          component: Home },
]

const { currentComponent, currentRoute } = useHashRoute(routes)
</script>

<template>
  <SplashScreen />
  <PublicDefaultLayout :current="currentRoute.key">
    <component :is="currentComponent" />
  </PublicDefaultLayout>
</template>
