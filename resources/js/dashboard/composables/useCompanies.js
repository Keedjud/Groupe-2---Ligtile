import { ref } from 'vue'
import { useFetchApi } from '@/composables/api/useFetchApi'

const listeEntreprises = ref([])
const chargement = ref(false)
const erreur = ref(null)

export function useCompanies() {
  const { fetchApi } = useFetchApi()

  function adapterDeApi(c) {
    if (!c) return null
    return {
      id: c.id,
      nom: c.name,
      nb_employes: c.nb_employee,
      taille: c.size_label || '',
      nb_collectes: c.collections_count ?? 0,
      adresse: {
        rue:    c.address?.street      || '',
        numero: c.address?.number      || '',
        npa:    c.address?.postal_code || '',
        ville:  c.address?.city        || '',
      },
      contact: {
        email:     c.contact?.email || '',
        telephone: c.contact?.phone || '',
      },
      collectes: (c.collections || []).map(col => ({
        id:         col.id,
        date_debut: col.start_date,
        date_fin:   col.end_date,
        capacity:   col.capacity,
      })),
    }
  }

  async function chargerEntreprises(search = '') {
    chargement.value = true
    erreur.value = null
    try {
      const url = search ? `/companies?search=${encodeURIComponent(search)}` : '/companies'
      const reponse = await fetchApi({ url })
      listeEntreprises.value = reponse.map(adapterDeApi)
    } catch (err) {
      console.error(err)
      erreur.value = 'Impossible de charger les entreprises.'
    } finally {
      chargement.value = false
    }
  }

  async function chargerEntreprise(id) {
    const reponse = await fetchApi({ url: `/companies/${id}` })
    return adapterDeApi(reponse)
  }

  async function creerEntreprise(donnees) {
    chargement.value = true
    erreur.value = null
    try {
      const payload = {
        name:        donnees.nom,
        nb_employee: Number(donnees.nb_employes),
        address: {
          street:      donnees.adresse.rue,
          number:      donnees.adresse.numero,
          postal_code: donnees.adresse.npa,
          city:        donnees.adresse.ville,
        },
        contact: {
          email: donnees.contact.email,
          phone: donnees.contact.telephone || null,
        },
      }
      const reponse = await fetchApi({ url: '/companies', method: 'POST', data: payload })
      const nouvelle = adapterDeApi(reponse)
      listeEntreprises.value.push(nouvelle)
      listeEntreprises.value.sort((a, b) => a.nom.localeCompare(b.nom))
      return nouvelle
    } catch (err) {
      console.error(err)
      erreur.value = err?.data?.message || "Erreur lors de la création."
      throw err
    } finally {
      chargement.value = false
    }
  }

  async function supprimerEntreprise(id) {
    chargement.value = true
    erreur.value = null
    try {
      await fetchApi({ url: `/companies/${id}`, method: 'DELETE' })
      listeEntreprises.value = listeEntreprises.value.filter(e => e.id !== Number(id))
    } catch (err) {
      console.error(err)
      erreur.value = err?.data?.message || 'Erreur lors de la suppression.'
      throw err
    } finally {
      chargement.value = false
    }
  }

  async function mettreAJourEntreprise(id, donnees) {
    chargement.value = true
    erreur.value = null
    try {
      const payload = {
        name:        donnees.nom,
        nb_employee: Number(donnees.nb_employes),
        address: {
          street:      donnees.adresse.rue,
          number:      donnees.adresse.numero,
          postal_code: donnees.adresse.npa,
          city:        donnees.adresse.ville,
        },
        contact: {
          email: donnees.contact.email,
          phone: donnees.contact.telephone || null,
        },
      }
      const reponse = await fetchApi({ url: `/companies/${id}`, method: 'PUT', data: payload })
      const updated = adapterDeApi(reponse)
      const idx = listeEntreprises.value.findIndex(e => e.id === Number(id))
      if (idx !== -1) listeEntreprises.value[idx] = updated
      return updated
    } catch (err) {
      console.error(err)
      erreur.value = 'Erreur lors de la mise à jour.'
      throw err
    } finally {
      chargement.value = false
    }
  }

  return {
    listeEntreprises,
    chargement,
    erreur,
    chargerEntreprises,
    chargerEntreprise,
    creerEntreprise,
    mettreAJourEntreprise,
    supprimerEntreprise,
  }
}
