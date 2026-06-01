/**
 * Gestion des collectes (CRUD). Données locales pour le moment.
 */
import { ref, computed } from 'vue'

// Données de test
const donneesMock = [
  {
    id: 1,
    entreprise: { nom: 'UBS', nb_employes: 5000, email: 'contact@ubs.ch', telephone: '+41 44 234 85 00' },
    adresse: { rue: 'Rue de la Banque', numero: '1', npa: '1200', ville: 'Genève' },
    date_debut: '2026-05-01',
    date_fin: '2026-06-30',
    couleur_principale: '#EB001B',
    couleur_secondaire: '#C44444',
    logo_url: '/images/UBS_logo.png',
    jeton_public: 'ubs-mock-token-2026-abc123',
    nb_inscrits: 150,
  },
  {
    id: 2,
    entreprise: { nom: 'Coop', nb_employes: 8000, email: 'contact@coop.ch', telephone: '+41 44 724 72 47' },
    adresse: { rue: 'Avenue Coop', numero: '50', npa: '8050', ville: 'Zurich' },
    date_debut: '2026-06-01',
    date_fin: '2026-07-15',
    couleur_principale: '#FF6B35',
    couleur_secondaire: '#C44444',
    logo_url: '/images/Coop_logo.png',
    jeton_public: 'coop-mock-token-2026-def456',
    nb_inscrits: 110,
  },
  {
    id: 3,
    entreprise: { nom: 'Nestlé', nb_employes: 3000, email: 'contact@nestle.ch', telephone: '+41 21 924 24 24' },
    adresse: { rue: 'Route Nestlé', numero: '77', npa: '1800', ville: 'Vevey' },
    date_debut: '2026-05-15',
    date_fin: '2026-07-15',
    couleur_principale: '#6B4423',
    couleur_secondaire: '#C44444',
    logo_url: '/images/Nestle-Logo-3126327959 2-1.png',
    jeton_public: 'nestle-mock-token-2026-ghi789',
    nb_inscrits: 180,
  },
  {
    id: 4,
    entreprise: { nom: 'Migros', nb_employes: 6000, email: 'contact@migros.ch', telephone: '+41 58 570 00 00' },
    adresse: { rue: 'Limmatstrasse', numero: '152', npa: '8005', ville: 'Zurich' },
    date_debut: '2026-04-01',
    date_fin: '2026-05-15',
    couleur_principale: '#FF6600',
    couleur_secondaire: '#C44444',
    logo_url: null,
    jeton_public: 'migros-mock-token-2026-jkl012',
    nb_inscrits: 210,
  },
  {
    id: 5,
    entreprise: { nom: 'Swisscom', nb_employes: 4500, email: 'contact@swisscom.ch', telephone: '+41 58 221 21 11' },
    adresse: { rue: 'Alte Tiefenaustrasse', numero: '6', npa: '3050', ville: 'Berne' },
    date_debut: '2026-05-01',
    date_fin: '2026-06-30',
    couleur_principale: '#0066CC',
    couleur_secondaire: '#C44444',
    logo_url: null,
    jeton_public: 'swisscom-mock-token-2026-mno345',
    nb_inscrits: 170,
  },
  {
    id: 6,
    entreprise: { nom: 'Swatch', nb_employes: 900, email: 'contact@swatch.ch', telephone: '+41 32 343 90 00' },
    adresse: { rue: 'Nicolas G. Hayek', numero: '1', npa: '2502', ville: 'Bienne' },
    date_debut: '2026-03-01',
    date_fin: '2026-03-31',
    couleur_principale: '#FF0000',
    couleur_secondaire: '#C44444',
    logo_url: null,
    jeton_public: 'swatch-mock-token-2026-pqr678',
    nb_inscrits: 120,
  },
  {
    id: 7,
    entreprise: { nom: 'SIG', nb_employes: 45, email: 'contact@sig-ge.ch', telephone: '+41 22 420 80 00' },
    adresse: { rue: 'Chemin du Château-Bloch', numero: '2', npa: '1219', ville: 'Le Lignon' },
    date_debut: '2026-02-01',
    date_fin: '2026-02-28',
    couleur_principale: '#339966',
    couleur_secondaire: '#C44444',
    logo_url: null,
    jeton_public: 'sig-mock-token-2026-stu901',
    nb_inscrits: 60,
  },
]

// État réactif partagé
const listeCollectes = ref([...donneesMock])
const chargement     = ref(false)
const erreur         = ref(null)
let   prochaineId    = ref(donneesMock.length + 1)

export function useCollectes() {
  const nombreTotal = computed(() => listeCollectes.value.length)

  /** Retourne une collecte par son id */
  function trouverParId(id) {
    return listeCollectes.value.find(c => c.id === Number(id)) ?? null
  }

  /** Simulation chargement depuis API */
  async function chargerCollectes() {
    chargement.value = true
    await new Promise(r => setTimeout(r, 300))
    chargement.value = false
  }

  /**
   * Crée une nouvelle collecte (mock)
   * @returns {Object} la collecte créée
   */
  async function creerCollecte(donnees) {
    chargement.value = true
    await new Promise(r => setTimeout(r, 350))
    const nouvelle = {
      id: prochaineId.value++,
      jeton_public: 'token-' + Math.random().toString(36).slice(2, 10),
      nb_inscrits: 0,
      ...donnees,
    }
    listeCollectes.value.push(nouvelle)
    chargement.value = false
    return nouvelle
  }

  /**
   * Met à jour une collecte existante (mock)
   */
  async function mettreAJourCollecte(id, donnees) {
    chargement.value = true
    await new Promise(r => setTimeout(r, 300))
    const index = listeCollectes.value.findIndex(c => c.id === Number(id))
    if (index !== -1) {
      listeCollectes.value[index] = { ...listeCollectes.value[index], ...donnees }
    }
    chargement.value = false
    return listeCollectes.value[index] ?? null
  }

  /**
   * Supprime une collecte (mock)
   */
  async function supprimerCollecte(id) {
    chargement.value = true
    await new Promise(r => setTimeout(r, 300))
    listeCollectes.value = listeCollectes.value.filter(c => c.id !== Number(id))
    chargement.value = false
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
