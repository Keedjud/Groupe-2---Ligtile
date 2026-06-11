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
      },
      adresse: {
        rue: c.venue_street || '',
        numero: c.venue_number != null ? String(c.venue_number) : '',
        npa: c.venue_postal_code != null ? String(c.venue_postal_code) : '',
        ville: c.venue_city || '',
      },
      contact_email: c.contact_email || '',
      contact_phone: c.contact_phone || '',
      date_debut: c.start_date,
      date_fin: c.end_date,
      couleur_principale: c.primary_color,
      logo_url: c.logo_url,
      onedoc_url: c.onedoc_url || '',
      kit_url: c.kit_url || '',
      capacity: c.capacity ?? null,
      jeton_public: c.public_token,
      lien_genere_le: c.link_generated_at ?? null,
      kit_envoye_le: c.kit_sent_at ?? null,
      nb_inscrits: c.nb_inscrits || 0,
    }
  }

  // Front → API
  function adapterVersApi(donnees) {
    const payload = {
      contact_email:     donnees.contact_email,
      contact_phone:     donnees.contact_phone,
      venue_street:      donnees.adresse.rue,
      venue_number:      donnees.adresse.numero,
      venue_postal_code: donnees.adresse.npa,
      venue_city:        donnees.adresse.ville,
      start_date:        donnees.date_debut,
      end_date:          donnees.date_fin,
      capacity:          donnees.capacity,
      primary_color:     donnees.couleur_principale,
      logo_url:          donnees.logo_url,
      onedoc_url:        donnees.onedoc_url,
      kit_url:           donnees.kit_url,
    }
    // company_id présent uniquement en création (jamais en édition)
    if (donnees.company_id) payload.company_id = donnees.company_id
    return payload
  }

  function trouverParId(id) {
    return listeCollectes.value.find(c => c.id === Number(id)) ?? null
  }

  function trierCollectes() {
    listeCollectes.value.sort((a, b) => {
      const dateA = new Date(a.date_fin).getTime()
      const dateB = new Date(b.date_fin).getTime()
      return dateB - dateA
    })
  }

  async function chargerCollectes() {
    chargement.value = true
    erreur.value = null
    try {
      const reponse = await fetchApi({ url: '/manage-collections' })
      listeCollectes.value = reponse.map(adapterDeApi)
      trierCollectes()
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
      trierCollectes()
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
        trierCollectes()
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
