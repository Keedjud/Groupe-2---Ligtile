import { ref } from 'vue'
import { useFetchApi } from '@/composables/api/useFetchApi'

export function useFormSubmit(url, onSuccess) {
    const submitting = ref(false)
    const submitted = ref(false)
    const formError = ref(null)

    const { fetchApi } = useFetchApi('/api/v1')

    async function submit(data) {
        formError.value = null
        submitting.value = true

        try {
            await fetchApi({ url, data })
            submitted.value = true
            onSuccess?.()
        } catch (err) {
            formError.value = err.data?.message || 'Une erreur est survenue lors de l\'envoi.'
        } finally {
            submitting.value = false
        }
    }

    return { submitting, submitted, formError, submit }
}
