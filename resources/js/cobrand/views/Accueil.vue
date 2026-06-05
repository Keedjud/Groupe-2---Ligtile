<script setup>


import { useCobrandSession } from "../composables/useCobrandSession";


const {
    companyName,
    nbEmployee,
    logoUrl,
    startDate,
    endDate,
    capacity,
    nbInscrits,
    placesRestantes,
    tauxRemplissage,
    contactEmail,
    contactPhone,
    venue,
    onedocUrl,
} = useCobrandSession();
const bloodTypes = [
    { label: 'O+', value: 'O+', color: 'var(--color-light-palette-red)', description: 'Dangereusement bas' },
    { label: 'A+', value: 'A+', color: 'var(--color-light-palette-orange)', description: 'Critique' },
    { label: 'AB+', value: 'AB+', color: 'var(--color-light-palette-orange)', description: 'Critique' },
    { label: 'B+', value: 'B+', color: 'var(--color-light-palette-yellow)', description: 'Bas' },
    { label: 'O-', value: 'O-', color: 'var(--color-light-palette-orange)', description: 'Critique' },
    { label: 'A-', value: 'A-', color: 'var(--color-light-palette-green)', description: 'Élevé' },
    { label: 'AB-', value: 'AB-', color: 'var(--color-vert-300)', description: 'Normal' },
    { label: 'B-', value: 'B-', color: 'var(--color-light-palette-yellow)', description: 'Bas' },
]

</script>
<template>

    <div class="min-h-screen text-[var(--color-texte-primary-dark)]">

        <main class="mx-auto max-w-[1300px] px-6 py-10 lg:px-[0px]">
            <section class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
                <div class="space-y-6  border border-[var(--color-violet-100)]  p-8 ">
                    <h1 class="text-h1 font-semibold text-[var(--color-texte-primary-dark)]">État de la collecte</h1>

                    <div class="space-y-4 ">
                        <p class="text-h4 font-medium text-[var(--color-texte-primary-dark)]">L’équipe {{ companyName }}
                            à Genève, compte {{ nbEmployee }} employé·e·s</p>
                        <p class="text-h4 text-[var(--color-texte-primary-dark)]"> {{ nbInscrits }} se sont
                            inscrit·e·s pour la <span class="text-cobrand-primary">collecte
                                actuelle</span></p>
                        <p class="text-h4 text-[var(--color-texte-primary-dark)]">Il reste <span
                                class="font-semibold">xx</span> places</p>

                        <a href="#/inscription"
                            class="inline-flex w-max items-center justify-center rounded-full bg-{{ collection?.primary_color }} px-8 py-3 text-regular font-semibold text-[var(--color-button-secondary)] shadow transition hover:bg-[var(--color-violet-800)]">Réservez
                            votre place</a>
                    </div>
                </div>

                <div class=" p-8 ">
                    <img :src="'/images/goute_sang_75.png'" alt=""
                        class="mx-auto h-[327px] w-full max-w-[196px] object-contain" />
                    <p class="mt-6 text-center text-h1 font-bold text-[var(--color-violet-900)]">72<span
                            class="text-h4 font-semibold">%</span></p>
                </div>
            </section>

            <section class="mt-16 p-8  lg:p-10">
                <div class="text-center">
                    <h2 class="text-h1 font-semibold text-[var(--color-violet-900)]">Pourquoi donner son sang ?</h2>
                    <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-{{ collection?.primary_color }}"></div>
                </div>

                <div class="mt-10 flex flex-col lg:flex-row lg:items-center">
                    <div class="w-full lg:w-[686px] lg:pr-10">
                        <img :src="'/images/goutes_ensemble_coeur.svg'" alt="" class="w-full object-contain" />
                    </div>

                    <div class="flex w-full flex-col gap-6">
                        <div>
                            <p class="text-h4 font-semibold text-[var(--color-violet-950)]">1. Sauver des vies</p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Chaque don peut aider
                                jusqu'à trois personnes</p>
                        </div>
                        <div>
                            <p class="text-h4 font-semibold text-[var(--color-violet-950)]">2. Les réserves sont basses
                            </p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">En donnant, vous aidez à
                                maintenir des réserves suffisantes pour faire face aux urgences</p>
                        </div>
                        <div>
                            <p class="text-h4 font-semibold text-[var(--color-violet-950)]">3. Un acte de solidarité</p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">C'est un acte simple
                                mais puissant. Une manière de montrer que vous vous souciez des autres.</p>
                        </div>
                        <div>
                            <p class="text-h4 font-semibold text-[var(--color-violet-950)]">4. C'est rapide et facile
                            </p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Le CTS se déplace sur
                                votre lieu de travail pour réduire encore plus vos désagréments.</p>
                        </div>
                        <div>
                            <p class="text-h4 font-semibold text-[var(--color-violet-950)]">5. Vous vous sentirez bien
                            </p>
                            <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Une bonne action procure
                                un sentiment de satisfaction et de bien-être.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mt-16 p-8 lg:p-10">
                <div>
                    <h2 class="text-h2 font-semibold text-[var(--color-texte-primary-dark)]">État actuel des réserves de
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

                        <img :src="'/images/sangOplus.png'" :alt="'Groupe sanguin ' + type.label"
                            class="h-28 w-28 object-contain" />


                    </div>
                </div>

                <div class="mt-8 p-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-light-palette-green)]"></span>
                            <span class="text-h1"><strong>Élevé</strong> Les stocks sont bien alimentés</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-vert-300)]"></span>
                            <span class="text-h1"><strong>Normal</strong> Les stocks suffisent pour plusieurs
                                jours</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-light-palette-yellow)]"></span>
                            <span class="text-small"><strong>Bas</strong> Les stocks diminuent, les dons sont
                                bienvenus</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-light-palette-orange)]"></span>
                            <span class="text-small"><strong>Critique</strong> Les stocks sont très limités, chaque don
                                compte</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-6 w-6 rounded-full bg-[var(--color-light-palette-red)]"></span>
                            <span class="text-small"><strong>Dangereusement bas</strong> Les réserves sont
                                insuffisantes, les besoins sont urgents</span>
                        </div>
                    </div>

                    <button
                        class="mt-6 rounded-full border border-[var(--color-light-palette-gray)] bg-[var(--color-light-palette-white)] px-4 py-2 text-small font-semibold text-[var(--color-texte-primary-dark)] transition hover:bg-[var(--color-beige-100)]">
                        Consulter le baromètre complet des HUG
                    </button>
                </div>
            </section>


            <section class="mt-16  p-8  lg:p-10">
                <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-{{ collection?.primary_color }}"></div>
                <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                    <div
                        class="rounded-[28px] bg-[var(--color-beige-50)] p-8 shadow-[0_10px_30px_rgba(104,23,100,0.08)]">
                        <img :src="'/images/composition_petites_gouttes.png'" alt=""
                            class="w-full rounded-[24px] object-contain" />
                    </div>
                    <div>
                        <h3 class="text-h4 font-semibold text-[var(--color-violet-900)]">Qui peut donner son sang ?</h3>
                        <p class="mt-4 text-regular text-[var(--color-texte-primary-dark)]">Âge, santé, voyages récents,
                            traitements… quelques critères peuvent influencer votre éligibilité. Faites une vérification
                            rapide avant votre inscription.</p>
                        <a href="#/inscription"
                            class="mt-6 inline-flex rounded-full bg-[var(--color-button-primary)] px-8 py-3 text-regular font-semibold text-[var(--color-button-secondary)] transition hover:bg-[var(--color-violet-800)]">Faire
                            le test d’éligibilité</a>
                    </div>
                </div>
            </section>
            <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-{{ collection?.primary_color }}"></div>
            <div class="mt-16    p-8 text-center ">
                <p class="text-h3 font-semibold text-[var(--color-violet-900)]">Je veux donner mon sang</p>
                <a href="#/inscription"
                    class="mt-6 inline-flex rounded-full bg-[var(--color-button-primary)] px-10 py-3 text-regular font-semibold text-[var(--color-button-secondary)] transition hover:bg-[var(--color-violet-800)]">Réservez
                    votre place</a>
            </div>
        </main>
    </div>
</template>
