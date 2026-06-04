import { ref } from 'vue'
import { useFetchApi } from '@/composables/api/useFetchApi'

// Validation + envoi du formulaire PME (page Information → /pme-contact).
// Même patron que usePollForm du cours : formErrors / globalError / validate / submit.
export function usePmeContactForm() {
  const { fetchApi } = useFetchApi('/api/v1')

  const formErrors = ref({})
  const globalError = ref(null)
  const submitting = ref(false)

  const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

  function clearFormErrors() {
    formErrors.value = {}
    globalError.value = null
  }

  function validate(form) {
    clearFormErrors()
    const e = {}
    if (!form.company_name.trim())        e.company_name = "Nom de l'entreprise requis."
    if (!regexEmail.test(form.email.trim())) e.email      = 'Adresse e-mail invalide.'
    if (!form.message.trim())             e.message       = 'Message requis.'
    formErrors.value = e
    return Object.keys(e).length === 0
  }

  async function submit(form) {
    if (!validate(form)) throw formErrors

    submitting.value = true
    clearFormErrors()
    try {
      return await fetchApi({ url: '/pme-contact', data: form })
    } catch (err) {
      globalError.value = err.data?.message ?? "Une erreur est survenue lors de l'envoi."
      throw globalError
    } finally {
      submitting.value = false
    }
  }

  return { formErrors, globalError, submitting, validate, submit, clearFormErrors }
}
