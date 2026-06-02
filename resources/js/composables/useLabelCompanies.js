import { useFetchApi } from './api/useFetchApi'

/**
 * Composable pour les appels API liés aux entreprises labellisées.
 * Retourne fetchApi (promesse) pour un usage avec async/await.
 */
export function useLabelCompanies() {
  const { fetchApi } = useFetchApi('/api/v1')

  return { fetchApi }
}
