/**
 * Routeur hash avec support des parametres dynamiques (:id)
 */
import { computed, onBeforeUnmount, onMounted, shallowRef, ref } from 'vue'

/**
 * @param {Array} tableauRoutes  - liste des routes { pattern, cle, component }
 *   pattern : chaîne avec segments optionnels ':param', ex. '#/collectes/:id/edit'
 */
export function useRouteurDashboard(tableauRoutes) {
  const routeParDefaut = tableauRoutes[0]
  const routeActive    = shallowRef(routeParDefaut)
  const parametres     = ref({})

  /** Convertit un pattern en regex et extrait les noms de params */
  function construireRegex(pattern) {
    const nomParams = []
    const regexStr  = pattern
      .replace(/[.*+?^${}()|[\]\\]/g, '\\$&') // échappe les caractères spéciaux
      .replace(/:([a-z_]+)/gi, (_, nom) => {
        nomParams.push(nom)
        return '([^/]+)'
      })
    return { regex: new RegExp('^' + regexStr + '$'), nomParams }
  }

  const routesComportement = tableauRoutes.map(r => ({
    ...r,
    ...construireRegex(r.pattern),
  }))

  function synchroDepuisUrl() {
    const rawHash = window.location.hash || routeParDefaut.pattern
    const hash = rawHash.split('?')[0]  // ignore les query params pour le matching
    for (const r of routesComportement) {
      const match = hash.match(r.regex)
      if (match) {
        routeActive.value = r
        // Peuple les paramètres extraits
        const vals = {}
        r.nomParams.forEach((nom, i) => { vals[nom] = match[i + 1] })
        parametres.value = vals
        return
      }
    }
    // Aucune correspondance → route par défaut
    routeActive.value = routeParDefaut
    parametres.value  = {}
  }

  function allerVers(hash) {
    window.history.pushState(null, '', hash)
    synchroDepuisUrl()
  }

  onMounted(() => {
    if (!window.location.hash) {
      window.history.replaceState(null, '', routeParDefaut.pattern)
    }
    synchroDepuisUrl()
    window.addEventListener('popstate', synchroDepuisUrl)
    window.addEventListener('hashchange', synchroDepuisUrl)
  })

  onBeforeUnmount(() => {
    window.removeEventListener('popstate', synchroDepuisUrl)
    window.removeEventListener('hashchange', synchroDepuisUrl)
  })

  synchroDepuisUrl()

  const composantActif = computed(() => routeActive.value.component)

  return { composantActif, routeActive, parametres, allerVers }
}
