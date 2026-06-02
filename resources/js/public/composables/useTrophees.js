import { computed } from 'vue'
import { useFetchApi } from '@/composables/api/useFetchApi'

export function useTrophees() {
  const { fetchApiToRef } = useFetchApi('/api/v1')

  const { data, error, loading, fetchNow } = fetchApiToRef({
    url: '/trophees',
    method: 'GET',
  })

  const podium = computed(() => data.value?.podium ?? null)
  const history = computed(() => data.value?.history ?? [])

  return { data, podium, history, loading, error, fetchNow }
}
