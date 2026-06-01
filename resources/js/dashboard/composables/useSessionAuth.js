/**
 * Gestion de session locale. Identifiants de test : admin@hug.ch / password123
 */
import { computed, ref } from 'vue'

// État partagé (singleton module)
const utilisateur   = ref(null)
const chargementAuth = ref(false)

export function useSessionAuth() {
  const estConnecte = computed(() => utilisateur.value !== null)

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
