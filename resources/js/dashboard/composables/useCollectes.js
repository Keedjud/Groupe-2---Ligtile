import { ref, computed } from 'vue'
import { useFetchApi } from '@/composables/api/useFetchApi'

// État réactif partagé
const listeCollectes = ref([])
const chargement = ref(false)
const erreur = ref(null)

export function useCollectes() {
  const nombreTotal = computed(() => listeCollectes.value.length)
  const { fetchApi } = useFetchApi()

  // API → Front
  function adapterDeApi(c) {
    if (!c) return null
    return {
      id: c.id,
      entreprise: {
        id: c.company?.id ?? c.company_id ?? null,
        nom: c.company?.name || '',
        nb_employes: c.company?.nb_employee || 0,
        email: c.company?.email || '',
        telephone: c.company?.phone_number || ''
      },
      adresse: {
        rue: c.address?.street || '',
        numero: c.address?.number != null ? String(c.address.number) : '',
        npa: c.address?.postal_code != null ? String(c.address.postal_code) : '',
        ville: c.address?.city || ''
      },
      date_debut: c.start_date,
      date_fin: c.end_date,
      couleur_principale: c.primary_color,
      couleur_secondaire: c.secondary_color,
      logo_url: c.logo_url,
      jeton_public: c.public_token,
      nb_inscrits: c.nb_registered || 0,
    }
  }

  // Front → API
  function adapterVersApi(donnees) {
    return {
      company: {
        name: donnees.entreprise.nom,
        nb_employee: donnees.entreprise.nb_employes,
        email: donnees.entreprise.email,
        phone_number: donnees.entreprise.telephone
      },
      address: {
        street: donnees.adresse.rue,
        number: donnees.adresse.numero,
        postal_code: donnees.adresse.npa,
        city: donnees.adresse.ville
      },
      start_date: donnees.date_debut,
      end_date: donnees.date_fin,
      primary_color: donnees.couleur_principale,
      secondary_color: donnees.couleur_secondaire,
      logo_url: donnees.logo_url
    }
  }

  function trouverParId(id) {
    return listeCollectes.value.find(c => c.id === Number(id)) ?? null
  }

  async function chargerCollectes() {
    chargement.value = true
    erreur.value = null
    try {
      const reponse = await fetchApi({ url: '/manage-collections' })
      listeCollectes.value = reponse.map(adapterDeApi)
    } catch (err) {
      console.error(err)
      erreur.value = "Impossible de charger les collectes."
    } finally {
      chargement.value = false
    }
  }

  async function creerCollecte(donnees) {
    chargement.value = true
    erreur.value = null
    try {
      const payload = adapterVersApi(donnees)
      const reponse = await fetchApi({
        url: '/manage-collections',
        method: 'POST',
        data: payload
      })
      const nouvelle = adapterDeApi(reponse)
      listeCollectes.value.unshift(nouvelle)
      return nouvelle
    } catch (err) {
      console.error(err)
      erreur.value = "Erreur lors de la création."
      throw err
    } finally {
      chargement.value = false
    }
  }

  async function mettreAJourCollecte(id, donnees) {
    chargement.value = true
    erreur.value = null
    try {
      const payload = adapterVersApi(donnees)
      const reponse = await fetchApi({
        url: `/manage-collections/${id}`,
        method: 'PUT',
        data: payload
      })
      const index = listeCollectes.value.findIndex(c => c.id === Number(id))
      if (index !== -1) {
        listeCollectes.value[index] = adapterDeApi(reponse)
      }
      return listeCollectes.value[index]
    } catch (err) {
      console.error(err)
      erreur.value = "Erreur lors de la mise à jour."
      throw err
    } finally {
      chargement.value = false
    }
  }

  async function supprimerCollecte(id) {
    chargement.value = true
    erreur.value = null
    try {
      await fetchApi({
        url: `/manage-collections/${id}`,
        method: 'DELETE'
      })
      listeCollectes.value = listeCollectes.value.filter(c => c.id !== Number(id))
    } catch (err) {
      console.error(err)
      erreur.value = "Erreur lors de la suppression."
      throw err
    } finally {
      chargement.value = false
    }
  }

  return {
    listeCollectes,
    chargement,
    erreur,
    nombreTotal,
    trouverParId,
    chargerCollectes,
    creerCollecte,
    mettreAJourCollecte,
    supprimerCollecte,
  }
}
