import { unref } from 'vue'

export const navLinks = [
  { key: 'trophee',      label: 'Trophée',      href: '/trophee' },
  { key: 'label',        label: 'Label CTS',    href: '/label' },
  { key: 'informations', label: 'Informations', href: '/informations' },
  { key: 'rdv',          label: 'Prendre RDV',  href: '/#prendre-rdv' },
]

export function useNavigation(current = null) {
  const isActive = (key) => unref(current) === key
  return { links: navLinks, isActive }
}
