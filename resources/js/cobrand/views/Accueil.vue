<script setup>

import { computed } from "vue";
import { useCobrandSession } from "../composables/useCobrandSession";
import BackgroundMotifs from "../components/BackgroundMotifs.vue";
import CoeurGouttes from "../components/CoeurGouttes.vue";


const {
    companyName,
    nbEmployee,
    nbInscrits,
    placesRestantes,
    tauxRemplissage,
    startDate,
    endDate,
} = useCobrandSession();

const mainImageSrc = computed(() => {
    const value = Number(tauxRemplissage.value ?? 0);
    if (value < 25) {
        return "/images/cobrand/home/goutte-sang-25.png";
    }
    if (value < 51) {
        return "/images/cobrand/home/goutte-sang-50.png";
    }
    if (value < 76) {
        return "/images/cobrand/home/goutte-sang-75.png";
    }
    return "/images/cobrand/home/goutte-sang-100.png";
});

const RING_RADIUS = 46;
const ringCircumference = 2 * Math.PI * RING_RADIUS;
const ringOffset = computed(() => {
    const value = Math.min(100, Math.max(0, Number(tauxRemplissage.value ?? 0)));
    return ringCircumference * (1 - value / 100);
});

const aujourdhui = new Date().toISOString().slice(0,10); // Format YYYY-MM-DD pour comparaison avec start/end date

const prochaineCollecte = computed(() => {
    if (!startDate.value) {
        return null;
    }
    const date = new Date(startDate.value);
    if (Number.isNaN(date.getTime())) {
        return null;
    }
    if (startDate.value > aujourdhui) {
    return new Intl.DateTimeFormat("fr-FR", {
        weekday: "short",
        day: "numeric",
        month: "long",
    }).format(date);
}
return null;
});


const collecteEnCours = computed(() => {
    if (!endDate.value || !startDate.value) {
        return null;
    }
    const date = new Date(endDate.value);
    if (Number.isNaN(date.getTime())) {
        return null;
    }
    if (endDate.value > aujourdhui && startDate.value <= aujourdhui) {
    return new Intl.DateTimeFormat("fr-FR", {
        weekday: "short",
        day: "numeric",
        month: "long",
    }).format(date);
}
return null;
});



const reserveLevels = [
    { title: 'Dangereusement bas', color: 'var(--color-light-palette-red)',    description: 'Les réserves sont insuffisantes, les besoins sont urgents.' },
    { title: 'Critique',           color: 'var(--color-light-palette-orange)', description: 'Les stocks sont très limités, chaque don compte.' },
    { title: 'Bas',                color: 'var(--color-light-palette-yellow)', description: 'Les stocks diminuent, les dons sont bienvenus.' },
    { title: 'Normal',             color: 'var(--color-vert-300)',             description: 'Les stocks suffisent pour plusieurs jours.' },
    { title: 'Élevé',              color: 'var(--color-light-palette-green)',  description: 'Les stocks sont bien alimentés.' },
];

const bloodTypes = [
    { label: 'O+',  value: 'O+',  img: '/images/cobrand/home/sang-o-plus.png',  color: 'var(--color-light-palette-red)',    description: 'Dangereusement bas' },
    { label: 'A+',  value: 'A+',  img: '/images/cobrand/home/sang-a-plus.png',  color: 'var(--color-light-palette-orange)', description: 'Critique' },
    { label: 'AB+', value: 'AB+', img: '/images/cobrand/home/sang-ab-plus.png', color: 'var(--color-light-palette-orange)', description: 'Critique' },
    { label: 'B+',  value: 'B+',  img: '/images/cobrand/home/sang-b-plus.png',  color: 'var(--color-light-palette-yellow)', description: 'Bas' },
    { label: 'O-',  value: 'O-',  img: '/images/cobrand/home/sang-o-neg.png',   color: 'var(--color-light-palette-orange)', description: 'Critique' },
    { label: 'A-',  value: 'A-',  img: '/images/cobrand/home/sang-a-neg.png',   color: 'var(--color-light-palette-green)',  description: 'Élevé' },
    { label: 'AB-', value: 'AB-', img: '/images/cobrand/home/sang-ab-neg.png',  color: 'var(--color-vert-300)',             description: 'Normal' },
    { label: 'B-',  value: 'B-',  img: '/images/cobrand/home/sang-b-neg.png',   color: 'var(--color-light-palette-yellow)', description: 'Bas' },
]

</script>
<template>
    <div class="relative w-full overflow-hidden px-4 py-10 lg:px-[60px]"
        style="--accent: var(--cobrand-primary-on-light, var(--color-light-palette-orange))">
        <BackgroundMotifs />
        <div class="relative z-10">
            <section class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center lg:gap-16">
                <div class="order-2 space-y-8 lg:order-1 lg:space-y-12">
                    <h1 class="text-h1 font-semibold text-[var(--color-texte-primary-dark)]">État de la collecte</h1>

                    <div class="space-y-5 lg:space-y-7">
                        <p class="text-h2  text-[var(--color-light-palette-black)]">L’équipe
                            <span class="font-semibold text-[color:var(--accent)]">{{ companyName }}</span>
                            {{ venue?.city }}, compte <span class="font-semibold text-[color:var(--accent)]">{{ nbEmployee }}</span> employé·e·s</p>
                        <p class="text-h2 text-[var(--color-light-palette-black)]">
                            <span class="font-semibold text-[color:var(--accent)]">{{ nbInscrits }}</span> personnes se sont
                            inscrites pour la <span class="font-semibold text-[color:var(--accent)]">collecte
                                actuelle</span></p>
                        <p class="text-h2 text-[var(--color-light-palette-black)]">Il reste
                            <span class="font-semibold text-[color:var(--accent)]">{{ placesRestantes }}</span> places</p>

                        <div class="flex flex-col items-center gap-3 pt-8 text-center sm:flex-row sm:items-center sm:gap-6 sm:pt-2 sm:text-left lg:pt-4">
                            <a href="#/quiz"
                                class="inline-flex w-max items-center justify-center rounded-full bg-[var(--cobrand-primary)] px-8 py-3 text-regular text-[var(--cobrand-primary-text)] shadow transition hover:-translate-y-0.5 hover:bg-[var(--cobrand-primary-dark)] hover:shadow-lg">Réservez
                                votre place</a>
                            <p v-if="prochaineCollecte" class="whitespace-nowrap text-regular text-[var(--color-texte-primary-dark)]">
                                Prochaine collecte : <span class="font-semibold capitalize">{{ prochaineCollecte }}</span>
                            </p>
                            <p v-if="collecteEnCours" class=" whitespace-nowrap text-regular text-[var(--color-texte-primary-dark)]">
                                Fin de la collecte : <span class="font-semibold capitalize">{{ collecteEnCours }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="order-1 flex w-full justify-center mb-10 lg:mb-0 lg:order-2 lg:w-auto">
    <div class="relative h-[240px] w-[240px] shrink-0 lg:h-[340px] lg:w-[340px]">
        <svg viewBox="0 0 100 100" class="absolute inset-0 h-full w-full -rotate-90">
            <circle cx="50" cy="50" :r="RING_RADIUS" fill="none"
                stroke="var(--color-light-palette-gray)" stroke-width="4" opacity="0.35" />
            <circle cx="50" cy="50" :r="RING_RADIUS" fill="none"
                stroke="var(--accent)" stroke-width="4" stroke-linecap="round"
                :stroke-dasharray="ringCircumference" :stroke-dashoffset="ringOffset" />
        </svg>
        <img :src="mainImageSrc" alt=""
            class="absolute left-1/2 top-1/2 h-[175px] w-auto -translate-x-1/2 -translate-y-1/2 object-contain drop-shadow-[0_14px_22px_rgba(104,23,100,0.22)] lg:h-[250px]" />
        <p class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full pt-2 text-center font-bold leading-none text-[color:var(--accent)]">
            <span class="text-[50px] lg:text-[72px]">{{ tauxRemplissage }}</span><span class="align-top text-[28px] lg:text-[40px]">%</span>
        </p>
    </div>
</div>

            </section>

            <section class="mt-16 py-8 lg:py-10">
                <div class="text-center">
                    <h2 class="text-h1 font-semibold text-[var(--color-violet-900)]">Pourquoi donner son sang ?</h2>
                    <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-[var(--cobrand-primary)]"></div>
                </div>

                <div class="mt-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:gap-12">
                    <div class="w-full lg:w-[820px] lg:shrink-0">
                        <CoeurGouttes class="w-full object-contain" />
                    </div>

                    <div class="flex w-full flex-col gap-6">
                        <div>
                            <p class="text-h2  text-[var(--color-violet-950)]"><span class="text-[color:var(--accent)]">1.</span> Sauver des vies</p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Chaque don peut aider
                                jusqu'à trois personnes</p>
                        </div>
                        <div>
                            <p class="text-h2  text-[var(--color-violet-950)]"><span class="text-[color:var(--accent)]">2.</span> Les réserves sont basses
                            </p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">En donnant, vous aidez à
                                maintenir des réserves suffisantes pour faire face aux urgences</p>
                        </div>
                        <div>
                            <p class="text-h2  text-[var(--color-violet-950)]"><span class="text-[color:var(--accent)]">3.</span> Un acte de solidarité</p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">C'est un acte simple
                                mais puissant. Une manière de montrer que vous vous souciez des autres.</p>
                        </div>
                        <div>
                            <p class="text-h2  text-[var(--color-violet-950)]"><span class="text-[color:var(--accent)]">4.</span> C'est rapide et facile
                            </p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Le CTS se déplace sur
                                votre lieu de travail pour réduire encore plus vos désagréments.</p>
                        </div>
                        <div>
                            <p class="text-h2 text-[var(--color-violet-950)]"><span class="text-[color:var(--accent)]">5.</span> Vous vous sentirez bien
                            </p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Une bonne action procure
                                un sentiment de satisfaction et de bien-être.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mt-16 py-8 lg:py-10">
                <div>
                    <h2 class="text-h1 font-semibold text-[var(--color-texte-primary-dark)]">État actuel des réserves de
                        sang à Genève</h2>
                    <p class="mt-3 text-regular text-[var(--color-texte-primary-dark)]">Aujourd'hui, certains groupes
                        sanguins nécessitent une attention particulière.</p>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="type in bloodTypes" :key="type.value"
                        class="rounded-[16px] bg-[var(--color-beige-25)] p-5 flex flex-col items-center">

                        <div class="w-full flex justify-start mb-3">
                            <span class="h-6 w-6 rounded-full" :style="{ backgroundColor: type.color }"></span>
                        </div>

                        <img :src="type.img" :alt="'Groupe sanguin ' + type.label"
                            class="h-28 w-28 object-contain" />

                        <span class="mt-3 text-h4 font-bold text-[var(--color-texte-primary-dark)]">{{ type.label }}</span>
                    </div>
                </div>

                <div class="mt-8 rounded-[24px] bg-[var(--color-light-palette-white)] p-8 shadow-[0px_4px_24px_0px_rgba(0,0,0,0.08)] lg:p-10">
                    <div class="flex gap-6 lg:gap-8">
                        <div class="w-3 shrink-0 self-stretch rounded-full"
                            :style="{ background: `linear-gradient(to bottom, ${reserveLevels.map((l) => l.color).join(', ')})` }">
                        </div>

                        <div class="flex flex-1 flex-col gap-5">
                            <div v-for="level in reserveLevels" :key="level.title"
                                class="flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4">
                                <div class="flex items-center gap-3 sm:w-[280px] sm:shrink-0">
                                    <span class="h-4 w-4 shrink-0 rounded-full"
                                        :style="{ backgroundColor: level.color }"></span>
                                    <span class="text-h4 font-bold text-[var(--color-texte-primary-dark)]">{{ level.title }}</span>
                                </div>
                                <p class="pl-7 text-regular text-[var(--color-texte-primary-dark)] sm:pl-0">{{ level.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <button

                    class="mt-6 block w-max mx-auto rounded-full border border-[var(--color-light-palette-gray)] bg-[var(--color-light-palette-white)] px-4 py-2 text-regular text-[color:var(--accent)] shadow-[0px_0px_4px_0px_rgba(0,0,0,0.25)] transition hover:-translate-y-0.5 hover:bg-[var(--color-beige-25)] hover:shadow-md sm:mx-0">
                    <a href="https://www.hug.ch/don-du-sang/barometre-don-du-sang-geneve">Consulter le baromètre complet des HUG</a>
                </button>
            </section>


            <section class="mt-16 py-8 lg:py-10">
                <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-[var(--cobrand-primary)]"></div>
                <div class="mt-10 grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                    <div>
                        <img :src="'/images/illustrations/composition-petites-gouttes.png'" alt=""
                            class="w-full rounded-[24px] object-contain" />
                    </div>
                    <div class="text-center sm:text-left">
                        <h3 class="text-h3 font-semibold text-[var(--color-texte-primary-dark)]">Qui peut donner son sang ?</h3>
                        <p class="mt-4 text-h4 text-[var(--color-texte-primary-dark)]">Âge, santé, voyages récents,
                            traitements… quelques critères peuvent influencer votre éligibilité. Faites une vérification
                            rapide avant votre inscription.</p>
                        <a href="#/quiz"
                            class="mt-6 inline-flex rounded-full border border-[var(--color-light-palette-gray)] bg-[var(--color-light-palette-white)] px-8 py-3 text-regular font-semibold text-[color:var(--accent)] shadow-[0px_0px_4px_0px_rgba(0,0,0,0.25)] transition hover:-translate-y-0.5 hover:bg-[var(--color-beige-25)] hover:shadow-md">Faire
                            le test d’éligibilité</a>
                    </div>
                </div>
            </section>
            <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-[var(--cobrand-primary)]"></div>
            <div class="mt-16 py-8 text-center">
                <p class="text-h1 font-semibold text-[var(--color-violet-900)]">Je veux donner mon sang</p>
                <a href="#/quiz"
                    class="mt-6 inline-flex rounded-full bg-[var(--cobrand-primary)] px-10 py-3 text-regular font-semibold text-[var(--cobrand-primary-text)] shadow transition hover:-translate-y-0.5 hover:bg-[var(--cobrand-primary-dark)] hover:shadow-lg">Réservez
                    votre place</a>
            </div>
        </div>
    </div>
</template>
