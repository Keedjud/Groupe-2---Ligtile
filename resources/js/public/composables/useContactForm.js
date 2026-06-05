import { ref } from 'vue'
import { useFetchApi } from '@/composables/api/useFetchApi'

// Validation + envoi du formulaire de prise de rendez-vous (page Home → /contact).
export function useContactForm() {
  const { fetchApi } = useFetchApi('/api/v1')

  const formErrors = ref({})
  const globalError = ref(null)
  const submitting = ref(false)

  const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  const regexNpa = /^\d{4}$/
  const regexTel = /^[+\d][\d\s()/.-]{5,}$/

  function clearFormErrors() {
    formErrors.value = {}
    globalError.value = null
  }

  function validate(form) {
    clearFormErrors()
    const e = {}
    if (!form.company_name.trim())                                  e.company_name    = "Nom de l'entreprise requis."
    if (!form.employees_count || Number(form.employees_count) < 1)  e.employees_count = "Nombre d'employés requis."
    if (!form.street.trim())                                        e.street          = 'Adresse requise.'
    if (!regexNpa.test(String(form.postal_code).trim()))            e.postal_code     = 'NPA invalide (4 chiffres).'
    if (!form.city.trim())                                          e.city            = 'Ville requise.'
    if (!regexEmail.test(form.email.trim()))                        e.email           = 'Adresse e-mail invalide.'
    if (!form.phone.trim())                                         e.phone           = 'Téléphone requis.'
    else if (!regexTel.test(form.phone.trim()))                     e.phone           = 'Téléphone invalide.'
    formErrors.value = e
    return Object.keys(e).length === 0
  }

  async function submit(form) {
    if (!validate(form)) throw formErrors

    submitting.value = true
    clearFormErrors()
    try {
      return await fetchApi({ url: '/contact', data: form })
    } catch (err) {
      globalError.value = err.data?.message ?? 'Une erreur est survenue. Veuillez réessayer.'
      throw globalError
    } finally {
      submitting.value = false
    }
  }

  return { formErrors, globalError, submitting, validate, submit, clearFormErrors }
}
