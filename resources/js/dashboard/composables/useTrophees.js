import { ref } from 'vue'
import { useFetchApi } from '@/composables/api/useFetchApi'

const listePodiums = ref([])
const chargement = ref(false)
const erreur = ref(null)

export function useTrophees() {
  const { fetchApi } = useFetchApi()

  async function chargerPodiums() {
    chargement.value = true
    erreur.value = null
    try {
      listePodiums.value = await fetchApi({ url: '/manage-trophees' })
    } catch {
      erreur.value = 'Impossible de charger les trophées.'
    } finally {
      chargement.value = false
    }
  }

  async function creerPodium(annee, ranks) {
    const reponse = await fetchApi({
      url: '/manage-trophees',
      method: 'POST',
      data: { year: annee, ranks },
    })
    await chargerPodiums()
    return reponse
  }

  async function mettreAJourPodium(annee, ranks) {
    const reponse = await fetchApi({
      url: `/manage-trophees/${annee}`,
      method: 'PUT',
      data: { ranks },
    })
    await chargerPodiums()
    return reponse
  }

  async function supprimerPodium(annee) {
    await fetchApi({ url: `/manage-trophees/${annee}`, method: 'DELETE' })
    listePodiums.value = listePodiums.value.filter(p => p.year !== annee)
  }

  return {
    listePodiums,
    chargement,
    erreur,
    chargerPodiums,
    creerPodium,
    mettreAJourPodium,
    supprimerPodium,
  }
}
