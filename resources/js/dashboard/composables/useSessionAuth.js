import { computed, ref } from 'vue'
import { useFetchApi, setDefaultHeaders } from '@/composables/api/useFetchApi'

// État partagé
const utilisateur = ref(null)
const chargementAuth = ref(false)

export function useSessionAuth() {
  const estConnecte = computed(() => utilisateur.value !== null)
  const { fetchApi } = useFetchApi()

  // Connexion
  async function connecter(email, password) {
    chargementAuth.value = true
    try {
      // Cookie CSRF
      await fetchApi({ url: '/sanctum/csrf-cookie', method: 'GET', baseUrl: '' })
      
      const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/)
      if (match) setDefaultHeaders({ 'X-XSRF-TOKEN': decodeURIComponent(match[1]) })

      // Connexion
      const response = await fetchApi({
        url: '/session/connect',
        method: 'POST',
        data: { email, password }
      })
      
      utilisateur.value = response.user
      return { succes: true }
    } catch (error) {
      return { succes: false, message: error.data?.message || 'Identifiants invalides.' }
    } finally {
      chargementAuth.value = false
    }
  }

  async function deconnecter() {
    try {
      await fetchApi({ url: '/session/disconnect', method: 'POST' })
    } catch (err) {
      console.error('Logout error:', err)
    }
    utilisateur.value = null
  }

  async function chargerUtilisateur() {
    try {
      const response = await fetchApi({ url: '/session/current-user', method: 'GET' })
      utilisateur.value = response
    } catch (err) {
      console.error('Session error:', err)
      utilisateur.value = null
    }
  }

  return {
    utilisateur,
    estConnecte,
    chargementAuth,
    connecter,
    deconnecter,
    chargerUtilisateur,
  }
}

