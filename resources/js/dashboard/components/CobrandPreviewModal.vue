<!-- CobrandPreviewModal — aperçu du haut de la page d'accueil cobrand avec les couleurs saisies -->
<script setup>
import { computed, watch, nextTick, ref } from 'vue'

const props = defineProps({
    primaryColor: { type: String, required: true },
    logoUrl:      { type: String, required: true },
    companyName:  { type: String, default: 'Entreprise' },
})

const open = defineModel({ type: Boolean, required: true })

// ── Calcul de la couleur de texte accessible (blanc ou noir) ─────────────────

function hexToRgb(hex) {
    const h = hex.replace('#', '')
    return [
        parseInt(h.slice(0, 2), 16),
        parseInt(h.slice(2, 4), 16),
        parseInt(h.slice(4, 6), 16),
    ]
}

function luminance([r, g, b]) {
    const f = c => {
        const v = c / 255
        return v <= 0.03928 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4
    }
    return 0.2126 * f(r) + 0.7152 * f(g) + 0.0722 * f(b)
}

function contrastRatio(l1, l2) {
    const [hi, lo] = l1 > l2 ? [l1, l2] : [l2, l1]
    return (hi + 0.05) / (lo + 0.05)
}

function accessibleText(hex) {
    try {
        const lum = luminance(hexToRgb(hex))
        return contrastRatio(1.0, lum) >= contrastRatio(0.0, lum) ? '#ffffff' : '#000000'
    } catch {
        return '#ffffff'
    }
}

// ── Variables CSS injectées sur le wrapper de la prévisualisation ─────────────

const themeVars = computed(() => ({
    '--cobrand-primary':          props.primaryColor,
    '--cobrand-primary-text':     accessibleText(props.primaryColor),
    '--cobrand-primary-on-light': props.primaryColor,
}))

// ── Accessibilité : focus au bouton de fermeture à l'ouverture ───────────────

const closeBtnRef = ref(null)

watch(open, async (ouvert) => {
    if (ouvert) {
        await nextTick()
        closeBtnRef.value?.focus()
    }
})

function onKeydown(e) {
    if (e.key === 'Escape') open.value = false
}
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="open"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
                @click.self="open = false"
                @keydown="onKeydown"
            >
                <div
                    class="relative flex h-[88dvh] w-full max-w-5xl flex-col overflow-hidden rounded-[25px] bg-white shadow-2xl"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="preview-modal-title"
                >
                    <!-- En-tête de la modale -->
                    <div class="flex shrink-0 items-center justify-between border-b border-violet-100 bg-white px-6 py-4 md:px-8">
                        <div>
                            <h2
                                id="preview-modal-title"
                                class="font-sans text-h4 font-semibold text-violet-900"
                            >
                                Aperçu du site cobrandé
                            </h2>
                            <p class="mt-0.5 font-sans text-small text-violet-400">
                                Rendu avec les couleurs et le logo saisis — les données réelles apparaîtront sur le vrai site.
                            </p>
                        </div>
                        <button
                            ref="closeBtnRef"
                            type="button"
                            aria-label="Fermer l'aperçu"
                            class="ml-4 flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-violet-400 transition-colors hover:bg-violet-100 hover:text-violet-700"
                            @click="open = false"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Zone de prévisualisation (scrollable) -->
                    <div class="flex-1 overflow-y-auto bg-[#f5f0e8]" :style="themeVars">

                        <!-- ── Faux CobrandHeader ─────────────────────────────── -->
                        <header class="sticky top-0 z-10 w-full bg-[#faf7f2] shadow-[0_2px_6px_rgba(0,0,0,0.10)]">
                            <div class="flex h-[55px] w-full items-center justify-between px-5 lg:px-[60px]">
                                <!-- Logo HUG + séparateur + logo entreprise -->
                                <div class="flex min-w-0 items-center gap-3 lg:gap-[22px]">
                                    <img
                                        :src="'/images/logos/logo-hug.png'"
                                        alt="HUG"
                                        class="h-7 w-auto shrink-0"
                                    />
                                    <span class="h-[30px] w-px shrink-0 bg-[#ccc]" aria-hidden="true"></span>
                                    <img
                                        :src="logoUrl"
                                        :alt="companyName"
                                        class="h-7 w-auto shrink-0 object-contain"
                                    />
                                </div>

                                <!-- Navigation -->
                                <nav class="flex items-center gap-7">
                                    <span
                                        class="font-sans text-regular font-bold"
                                        :style="{ color: 'var(--cobrand-primary)' }"
                                    >Accueil</span>
                                    <span class="font-sans text-regular text-[#444] transition-colors hover:text-violet-500">
                                        Prévention
                                    </span>
                                    <span class="font-sans text-regular text-[#444] transition-colors hover:text-violet-500">
                                        Formulaire
                                    </span>
                                </nav>
                            </div>
                        </header>

                        <!-- ── Section principale : État de la collecte ──────── -->
                        <section class="mx-auto max-w-[1300px] px-5 py-10 lg:px-10">
                            <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">

                                <!-- Carte gauche -->
                                <div class="space-y-6 rounded-2xl border border-[#d8ceeb] bg-white/80 p-8 shadow-sm">
                                    <h1 class="font-sans text-h2 font-semibold text-[#1a0a2e]">
                                        État de la collecte
                                    </h1>
                                    <div class="space-y-4">
                                        <p class="font-sans text-h3 text-[#1a0a2e]">
                                            L'équipe
                                            <span class="font-semibold" :style="{ color: 'var(--cobrand-primary)' }">
                                                {{ companyName }}
                                            </span>
                                            compte
                                            <span class="font-semibold" :style="{ color: 'var(--cobrand-primary)' }">
                                                —
                                            </span>
                                            employé·e·s
                                        </p>
                                        <p class="font-sans text-h3 text-[#1a0a2e]">
                                            <span class="font-bold" :style="{ color: 'var(--cobrand-primary)' }">0</span>
                                            se sont inscrit·e·s pour la
                                            <span class="font-semibold" :style="{ color: 'var(--cobrand-primary)' }">
                                                collecte actuelle
                                            </span>
                                        </p>
                                        <p class="font-sans text-h3 text-[#1a0a2e]">
                                            Il reste
                                            <span class="font-bold" :style="{ color: 'var(--cobrand-primary)' }">—</span>
                                            places
                                        </p>

                                        <!-- CTA principal -->
                                        <a
                                            class="inline-flex w-max cursor-pointer items-center justify-center rounded-full px-8 py-3 font-sans text-regular font-semibold shadow transition"
                                            :style="{
                                                backgroundColor: 'var(--cobrand-primary)',
                                                color: 'var(--cobrand-primary-text)',
                                            }"
                                        >
                                            Réservez votre place
                                        </a>
                                    </div>
                                </div>

                                <!-- Carte droite : goutte + % -->
                                <div class="flex flex-col items-center rounded-2xl p-8">
                                    <img
                                        :src="'/images/cobrand/home/goutte-sang-25.png'"
                                        alt=""
                                        class="mx-auto h-[220px] w-full max-w-[140px] select-none object-contain"
                                    />
                                    <p
                                        class="mt-5 text-center font-sans text-h1 font-semibold"
                                        :style="{ color: 'var(--cobrand-primary)' }"
                                    >
                                        0<span class="text-h2 font-semibold">%</span>
                                    </p>
                                </div>
                            </div>
                        </section>

                        <!-- ── Section "Pourquoi donner" (titre + séparateur) ── -->
                        <section class="mx-auto max-w-[1300px] px-5 pb-12 lg:px-10">
                            <div class="rounded-2xl bg-white/70 p-8 lg:p-10 shadow-sm">
                                <div class="text-center">
                                    <h2 class="font-sans text-h1 font-semibold text-[#1a0a2e]">
                                        Pourquoi donner son sang ?
                                    </h2>
                                    <div
                                        class="mx-auto mt-4 h-1 w-[216px] rounded-full"
                                        :style="{ backgroundColor: 'var(--cobrand-primary)' }"
                                    ></div>
                                </div>

                                <div class="mt-8 flex flex-col gap-5">
                                    <div v-for="(raison, i) in ['Sauver des vies — chaque don peut aider jusqu\'à 3 personnes.', 'Les réserves sont basses — en donnant, vous aidez à maintenir des stocks suffisants.', 'Un acte de solidarité simple mais puissant.']" :key="i">
                                        <p class="font-sans text-h4 text-[#1a0a2e]">
                                            <span class="font-bold" :style="{ color: 'var(--cobrand-primary)' }">
                                                {{ i + 1 }}.
                                            </span>
                                            {{ raison }}
                                        </p>
                                    </div>
                                </div>

                                <!-- CTA secondaire -->
                                <a
                                    class="mt-8 inline-flex cursor-pointer items-center justify-center rounded-full px-8 py-3 font-sans text-regular font-semibold shadow transition"
                                    :style="{
                                        backgroundColor: 'var(--cobrand-primary)',
                                        color: 'var(--cobrand-primary-text)',
                                    }"
                                >
                                    Faire le test d'éligibilité
                                </a>
                            </div>
                        </section>

                    </div><!-- fin zone prévisualisation -->
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
