import { toValue } from 'vue'

// Mapping des noms d'entreprises vers les fichiers logos locaux (fallback)
const localLogos = {
    'coop': '/images/logos/logo-coop.png',
    'migros': '/images/logos/logo-migros.png',
    'nestlé': '/images/logos/logo-nestle.png',
    'ubs': '/images/logos/logo-ubs.png',
}

/**
 * Retrouve une entreprise par son rang dans un tableau de companies.
 * Accepte une ref ou une valeur brute via toValue().
 */
export function companyForRank(companies, rank) {
    const list = toValue(companies)
    return list?.find(c => c.rank == rank) ?? null
}

/**
 * Composables pour la logique des logos du podium.
 * @param {Ref|Array} companies - ref ou tableau brut des entreprises du podium
 */
export function usePodiumLogos(companies) {
    function getLocalLogo(company) {
        if (!company) return null
        return localLogos[company.name?.toLowerCase()?.trim()] ?? null
    }

    // Priorité : URL de la DB (logo_url), sinon fichier local
    function logoForRank(rank) {
        const company = companyForRank(companies, rank)
        if (!company) return null
        return company.logo_url ?? getLocalLogo(company) ?? null
    }

    // Appelé quand une image logo casse (404, etc.) : essaie le fallback local
    function onLogoError(e, rank) {
        const company = companyForRank(companies, rank)
        const local = getLocalLogo(company)
        if (local) {
            e.target.src = local
        } else {
            e.target.style.display = 'none'
        }
    }

    return { companyForRank: (rank) => companyForRank(companies, rank), logoForRank, onLogoError }
}
