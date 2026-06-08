<script setup>

import { computed } from "vue";
import { useCobrandSession } from "../composables/useCobrandSession";


const {
    companyName,
    nbEmployee,
    nbInscrits,
    placesRestantes,
    tauxRemplissage,
} = useCobrandSession();

const mainImageSrc = computed(() => {
    const value = Number(tauxRemplissage.value ?? 0);
    if (value < 25) {
        return "/images/goutte-sang-25.png";
    }
    if (value < 50) {
        return "/images/goutte-sang-50.png";
    }
    if (value < 75) {
        return "/images/goutte-sang-75.png";
    }
    return "/images/goutte-sang-100.png";
});

const bloodTypes = [
    { label: 'O+',  value: 'O+',  img: '/images/sang-o-plus.png',  color: 'var(--color-light-palette-red)',    description: 'Dangereusement bas' },
    { label: 'A+',  value: 'A+',  img: '/images/sang-a-plus.png',  color: 'var(--color-light-palette-orange)', description: 'Critique' },
    { label: 'AB+', value: 'AB+', img: '/images/sang-ab-plus.png', color: 'var(--color-light-palette-orange)', description: 'Critique' },
    { label: 'B+',  value: 'B+',  img: '/images/sang-b-plus.png',  color: 'var(--color-light-palette-yellow)', description: 'Bas' },
    { label: 'O-',  value: 'O-',  img: '/images/sang-o-neg.png',   color: 'var(--color-light-palette-orange)', description: 'Critique' },
    { label: 'A-',  value: 'A-',  img: '/images/sang-a-neg.png',   color: 'var(--color-light-palette-green)',  description: 'Élevé' },
    { label: 'AB-', value: 'AB-', img: '/images/sang-ab-neg.png',  color: 'var(--color-vert-300)',             description: 'Normal' },
    { label: 'B-',  value: 'B-',  img: '/images/sang-b-neg.png',   color: 'var(--color-light-palette-yellow)', description: 'Bas' },
]

</script>
<template>
    <div class="mx-auto max-w-[1300px] px-6 py-10 lg:px-0">
            <section class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
                <div class="space-y-6  border border-[var(--color-violet-100)]  p-8 ">
                    <h1 class="text-h1 font-semibold text-[var(--color-texte-primary-dark)]">État de la collecte</h1>

                    <div class="space-y-4 ">
                        <p class="text-h2  text-[var(--color-light-palette-black)]">L’équipe {{ companyName }}
                            à Genève, compte {{ nbEmployee }} employé·e·s</p>
                        <p class="text-h2 text-[var(--color-light-palette-black)]"> {{ nbInscrits }} se sont
                            inscrit·e·s pour la <span class="text-[var(--cobrand-primary)]">collecte
                                actuelle</span></p>
                        <p class="text-h2 text-[var(--color-light-palette-black)]">Il reste {{ placesRestantes }} places</p>

                        <a href="#/inscription"
                            class="inline-flex w-max items-center justify-center rounded-full bg-[var(--cobrand-primary)] px-8 py-3 text-regular text-[var(--cobrand-primary-text)] shadow transition hover:bg-[var(--cobrand-secondary)]">Réservez
                            votre place</a>
                    </div>
                </div>

                <div class=" p-8 ">
                    <img :src="mainImageSrc" alt=""
                        class="mx-auto h-[327px] w-full max-w-[196px] object-contain" />
                    <p class="mt-6 text-center text-h1 font-semibold text-[var(--color-violet-900)]">{{ tauxRemplissage }}<span
                            class="text-h1 font-semibold">%</span></p>
                </div>
            </section>

            <section class="mt-16 p-8  lg:p-10">
                <div class="text-center">
                    <h2 class="text-h1 font-semibold text-[var(--color-violet-900)]">Pourquoi donner son sang ?</h2>
                    <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-[var(--cobrand-primary)]"></div>
                </div>

                <div class="mt-10 flex flex-col lg:flex-row lg:items-center">
                    <div class="w-full lg:w-[686px] lg:pr-10">
                        <img :src="'/images/gouttes-ensemble-coeur.svg'" alt="" class="w-full object-contain" />
                    </div>

                    <div class="flex w-full flex-col gap-6">
                        <div>
                            <p class="text-h2  text-[var(--color-violet-950)]"><span class="text-[var(--cobrand-primary)]">1.</span> Sauver des vies</p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Chaque don peut aider
                                jusqu'à trois personnes</p>
                        </div>
                        <div>
                            <p class="text-h2  text-[var(--color-violet-950)]"><span class="text-[var(--cobrand-primary)]">2.</span> Les réserves sont basses
                            </p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">En donnant, vous aidez à
                                maintenir des réserves suffisantes pour faire face aux urgences</p>
                        </div>
                        <div>
                            <p class="text-h2  text-[var(--color-violet-950)]"><span class="text-[var(--cobrand-primary)]">3.</span> Un acte de solidarité</p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">C'est un acte simple
                                mais puissant. Une manière de montrer que vous vous souciez des autres.</p>
                        </div>
                        <div>
                            <p class="text-h2  text-[var(--color-violet-950)]"><span class="text-[var(--cobrand-primary)]">4.</span> C'est rapide et facile
                            </p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Le CTS se déplace sur
                                votre lieu de travail pour réduire encore plus vos désagréments.</p>
                        </div>
                        <div>
                            <p class="text-h2 text-[var(--color-violet-950)]"><span class="text-[var(--cobrand-primary)]">5.</span> Vous vous sentirez bien
                            </p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Une bonne action procure
                                un sentiment de satisfaction et de bien-être.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mt-16 p-8 lg:p-10">
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


                    </div>
                </div>

                <div class="mt-8 p-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-light-palette-green)]"></span>
                            <span class="text-h3 text-[var(--color-texte-primary-dark)]">Élevé</span>
                                <span class="text-h5 text-[var(--color-texte-primary-dark)]">Les stocks sont bien alimentés</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-vert-300)]"></span>
                            <span class="text-h3 text-[var(--color-texte-primary-dark)]">Normal</span>
                                <span class="text-h5 text-[var(--color-texte-primary-dark)]"> Les stocks suffisent pour plusieurs
                                jours</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-light-palette-yellow)]"></span>
                            <span class="text-h3 text-[var(--color-texte-primary-dark)]">Bas</span>
                                <span class="text-h5 text-[var(--color-texte-primary-dark)]">Les stocks diminuent, les dons sont
                                bienvenus</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-light-palette-orange)]"></span>
                            <span class="text-h3 text-[var(--color-texte-primary-dark)]">Critique</span>
                                <span class="text-h5 text-[var(--color-texte-primary-dark)]"> Les stocks sont très limités, chaque don
                                compte</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-light-palette-red)]"></span>
                            <span class="text-h3 text-[var(--color-texte-primary-dark)]">Dangereusement bas</span>
                                <span class="text-h5 text-[var(--color-texte-primary-dark)]">Les réserves sont
                                insuffisantes, les besoins sont urgents</span>
                        </div>
                    </div>

                    <button
                        class="mt-6 rounded-full border border-[var(--color-light-palette-gray)] bg-[var(--color-light-palette-white)] px-4 py-2 text-regular text-[var(--cobrand-primary)] transition hover:bg-[var(--cobrand-secondary)]">
                        Consulter le baromètre complet des HUG
                    </button>
                </div>
            </section>


            <section class="mt-16  p-8  lg:p-10">
                <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-[var(--cobrand-primary)]"></div>
                <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                    <div
                        class=" p-8 ">
                        <img :src="'/images/illustrations/composition-petites-gouttes.png'" alt=""
                            class="w-full rounded-[24px] object-contain" />
                    </div>
                    <div>
                        <h3 class="text-h3 font-semibold text-[var(--color-texte-primary-dark)]">Qui peut donner son sang ?</h3>
                        <p class="mt-4 text-h4 text-[var(--color-texte-primary-dark)]">Âge, santé, voyages récents,
                            traitements… quelques critères peuvent influencer votre éligibilité. Faites une vérification
                            rapide avant votre inscription.</p>
                        <a href="#/inscription"
                            class="mt-6 inline-flex rounded-full bg-[var(--cobrand-primary)] px-8 py-3 text-regular font-semibold text-[var(--cobrand-primary-text)] transition hover:bg-[var(--cobrand-secondary)]">Faire
                            le test d’éligibilité</a>
                    </div>
                </div>
            </section>
            <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-[var(--cobrand-primary)]"></div>
            <div class="mt-16    p-8 text-center ">
                <p class="text-h1 font-semibold text-[var(--color-violet-900)]">Je veux donner mon sang</p>
                <a href="#/inscription"
                    class="mt-6 inline-flex rounded-full bg-[var(--cobrand-primary)] px-10 py-3 text-regular font-semibold text-[var(--cobrand-primary-text)] transition hover:bg-[var(--cobrand-secondary)]">Réservez
                    votre place</a>
            </div>
    </div>
</template>
