import { ref } from 'vue'

export function useDisclosure(initial = false) {
  const isOpen = ref(initial)
  const toggle = () => { isOpen.value = !isOpen.value }
  const close = () => { isOpen.value = false }
  return { isOpen, toggle, close }
}
