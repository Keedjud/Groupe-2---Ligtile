<script setup>
import { useDisclosure } from "@/composables/useDisclosure";

defineProps({
    current: { type: String, default: null },
    logo: { type: String, default: "/images/logos/logo-hug.png" },
});

const links = [
    { key: "accueil", label: "Accueil", href: "#/accueil" },
    { key: "inscription", label: "Formulaire", href: "#/inscription" },
    { key: "prevention", label: "Prévention", href: "#/prevention" },
];

const { isOpen, toggle, close } = useDisclosure();
</script>

<template>
    <header
        class="relative w-full bg-form-bg shadow-[0_4px_4px_rgba(0,0,0,0.10)]"
    >
        <div
            class="flex h-[55px] w-full items-center justify-between px-4 py-1.5 lg:px-[60px]"
        >
            <div class="flex min-w-0 items-center gap-3 lg:gap-[22px]">
                <a href="#/accueil" class="shrink-0">
                    <img
                        :src="logo"
                        alt="HUG - Hôpitaux Universitaires de Genève"
                        class="h-7 w-auto"
                    />
                </a>
                <span
                    class="h-[30px] w-px shrink-0 bg-gris-50 lg:h-[35px]"
                    aria-hidden="true"
                ></span>
                <span
                    class="truncate text-h5 font-bold tracking-wide text-light-palette-orange lg:text-h4"
                    >MIGROS</span
                >
            </div>

            <nav class="hidden items-center gap-7 lg:flex">
                <a
                    v-for="link in links"
                    :key="link.key"
                    :href="link.href"
                    class="font-sans text-regular transition-colors hover:text-violet-500"
                    :class="
                        current === link.key
                            ? 'font-bold text-violet-900'
                            : 'text-texte-primary-dark'
                    "
                >
                    {{ link.label }}
                </a>
            </nav>

            <button
                type="button"
                class="flex h-10 w-10 items-center justify-center rounded-full text-violet-950 lg:hidden"
                aria-label="Menu"
                :aria-expanded="isOpen"
                @click="toggle"
            >
                <svg
                    v-if="!isOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-7 w-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 7h16M4 12h16M4 17h16"
                    />
                </svg>
                <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-7 w-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 6l12 12M18 6L6 18"
                    />
                </svg>
            </button>
        </div>

        <Transition
            enter-active-class="transition duration-150 ease-out origin-top"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-100 ease-in origin-top"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <nav
                v-show="isOpen"
                class="absolute inset-x-0 top-full z-30 border-t border-black/10 bg-form-bg px-6 pb-4 pt-2 shadow-[0_4px_4px_rgba(0,0,0,0.10)] lg:hidden"
            >
                <a
                    v-for="link in links"
                    :key="link.key"
                    :href="link.href"
                    @click="close"
                    :class="
                        current === link.key
                            ? 'block border-b border-black/5 py-3 font-sans text-h5 font-bold text-violet-900 last:border-0'
                            : 'block border-b border-black/5 py-3 font-sans text-h5 text-texte-primary-dark last:border-0 hover:text-violet-500'
                    "
                >
                    {{ link.label }}
                </a>
            </nav>
        </Transition>
    </header>
</template>
