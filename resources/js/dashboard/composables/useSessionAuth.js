<<<<<<< HEAD
/**
 * Gestion de session locale. Identifiants de test : admin@hug.ch / password123
 */
import { computed, ref } from 'vue'

// État partagé (singleton module)
const utilisateur   = ref(null)
=======
import { computed, ref } from 'vue'
import { useFetchApi, setDefaultHeaders } from '@/composables/api/useFetchApi'

// État partagé
const utilisateur = ref(null)
>>>>>>> develop
const chargementAuth = ref(false)

export function useSessionAuth() {
  const estConnecte = computed(() => utilisateur.value !== null)
<<<<<<< HEAD

  /**
   * Connexion mockée
   * @returns {{ succes: boolean, message?: string }}
   */
  async function connecter(email, motDePasse) {
    chargementAuth.value = true
    // Simule un délai réseau
    await new Promise(r => setTimeout(r, 400))
    chargementAuth.value = false

    if (email === 'admin@hug.ch' && motDePasse === 'password123') {
      utilisateur.value = { id: 1, email, prenom: 'Admin', nom: 'HUG' }
      // Persiste en sessionStorage pour survivre à un rechargement
      sessionStorage.setItem('dashboard_user', JSON.stringify(utilisateur.value))
      return { succes: true }
    }
    return { succes: false, message: 'Identifiants invalides.' }
  }

  /** Déconnexion */
  function deconnecter() {
    utilisateur.value = null
    sessionStorage.removeItem('dashboard_user')
  }

  /**
   * Récupère l'utilisateur depuis sessionStorage (mock de fetchUser Sanctum)
   * Retourne silencieusement si non connecté.
   */
  function chargerUtilisateur() {
    const sauvegarde = sessionStorage.getItem('dashboard_user')
    if (sauvegarde) {
      try { utilisateur.value = JSON.parse(sauvegarde) } catch { /* ignoré */ }
=======
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
>>>>>>> develop
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
<<<<<<< HEAD
=======

>>>>>>> develop
