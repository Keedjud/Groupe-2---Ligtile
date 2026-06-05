<<<<<<< HEAD
<template>
    <router-view />
</template>

<script setup>
defineProps({
    collecteId: { type: String, required: true },
})
=======
<script setup>
import { ref, onMounted } from 'vue'
import { useFetchApi } from '@/composables/api/useFetchApi'
import CobrandHeader from './components/CobrandHeader.vue'
import { useHashRoute } from '@/composables/router'
import Accueil from './views/Accueil.vue'
import Prevention from './views/Prevention.vue'
import Quiz from './views/Quiz.vue'
import Redirect from './views/Redirect.vue'

const props = defineProps({ collecteToken: String })

const collection = ref(null)
const { fetchApi } = useFetchApi('/api/v1')

onMounted(async () => {
  collection.value = await fetchApi(`/cobrand/${props.collecteToken}`)
})

const routes = [
  { hash: '#/accueil',     key: 'accueil',     component: Accueil },
  { hash: '#/prevention',  key: 'prevention',  component: Prevention },
  { hash: '#/quiz',        key: 'quiz',        component: Quiz },
  { hash: '#/inscription', key: 'inscription', component: Redirect },
]

const { currentComponent, currentRoute } = useHashRoute(routes)
>>>>>>> feature/dashboard
</script>

<template>
  <div class="min-h-screen bg-beige-50 text-violet-900">
    <CobrandHeader :current="currentRoute.key" />
    <main class="mx-auto max-w-[1300px] px-6 py-10 lg:px-0">
      <component :is="currentComponent" :collection="collection" />
    </main>
  </div>
</template>
