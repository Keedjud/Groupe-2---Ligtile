<template>
  <div class="min-h-screen bg-[var(--color-beige-50)] text-[var(--color-texte-primary-dark)]">

    <main class="mx-auto max-w-[1512px] px-6 py-10 lg:px-[60px]">
      <section class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
        <div class="space-y-6 rounded-[32px] border border-[var(--color-violet-100)] bg-[var(--color-beige-50)] p-8 shadow-[0_24px_60px_rgba(104,23,100,0.08)]">
          <h1 class="text-h1 font-semibold text-[var(--color-violet-900)]">État de la collecte</h1>

          <div class="space-y-4 rounded-[28px] bg-white p-6 shadow-[0_10px_30px_rgba(104,23,100,0.08)]">
            <p class="text-h4 font-medium text-[var(--color-texte-primary-dark)]">L’équipe <span class="font-semibold">{entreprise}</span> à Genève, compte <span class="font-semibold">xx</span> employé·e·s</p>
            <p class="text-h4 text-[var(--color-texte-primary-dark)]"><span class="font-semibold">xx</span> se sont inscrit·e·s pour la <span class="text-[var(--color-light-palette-orange)]">collecte actuelle</span></p>
            <p class="text-h4 text-[var(--color-texte-primary-dark)]">Il reste <span class="font-semibold">xx</span> places</p>

            <a href="#/inscription" class="inline-flex w-max items-center justify-center rounded-full bg-[var(--color-button-primary)] px-8 py-3 text-regular font-semibold text-[var(--color-button-secondary)] shadow transition hover:bg-[var(--color-violet-800)]">Réservez votre place</a>
          </div>
        </div>

        <div class="rounded-[32px] bg-white p-8 shadow-[0_24px_60px_rgba(104,23,100,0.08)]">
          <img src="goute_sang_75.png" alt="Illustration collecte" class="mx-auto h-[327px] w-full max-w-[196px] object-cover" />
          <p class="mt-6 text-center text-h1 font-bold text-[var(--color-violet-900)]">72<span class="text-h4 font-semibold">%</span></p>
        </div>
      </section>

      <section class="mt-16 rounded-[32px] border border-[var(--color-violet-100)] bg-white p-8 shadow-[0_24px_60px_rgba(104,23,100,0.08)] lg:p-10">
        <div class="text-center">
          <h2 class="text-h1 font-semibold text-[var(--color-violet-900)]">Pourquoi donner son sang ?</h2>
          <div class="mx-auto mt-4 h-1 w-[216px] rounded-full bg-[var(--color-light-palette-orange)]"></div>
        </div>

        <div class="mt-10 flex flex-col gap-10 lg:flex-row lg:items-start">
          <div class="w-full lg:w-[686px] rounded-[28px] bg-[var(--color-violet-100)] p-6 shadow-[0_10px_30px_rgba(104,23,100,0.08)]">
            <img src="goutes_ensemble_coeur.svg" alt="Illustration Pourquoi donner" class="h-[389px] w-full rounded-[20px] object-cover" />
          </div>

          <div class="flex w-full flex-col gap-6">
            <div class="space-y-3">
              <div class="flex items-start gap-4">
                <span class="text-[var(--color-light-palette-orange)] text-[32px] font-semibold">1.</span>
                <div>
                  <p class="text-h4 font-semibold text-[var(--color-violet-950)]">Sauver des vies</p>
                  <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Chaque don peut aider jusqu’à trois personnes</p>
                </div>
              </div>
            </div>

            <div class="space-y-3">
              <div class="flex items-start gap-4">
                <span class="text-[var(--color-light-palette-orange)] text-[32px] font-semibold">2.</span>
                <div>
                  <p class="text-h4 font-semibold text-[var(--color-violet-950)]">Les réserves sont basses</p>
                  <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">En donnant, vous aidez à maintenir des réserves suffisantes pour faire face aux urgences</p>
                </div>
              </div>
            </div>

            <div class="space-y-3">
              <div class="flex items-start gap-4">
                <span class="text-[var(--color-light-palette-orange)] text-[32px] font-semibold">3.</span>
                <div>
                  <p class="text-h4 font-semibold text-[var(--color-violet-950)]">Un acte de solidarité</p>
                  <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">C’est un acte simple mais puissant. Une manière de montrer que vous vous souciez des autres.</p>
                </div>
              </div>
            </div>

            <div class="space-y-3">
              <div class="flex items-start gap-4">
                <span class="text-[var(--color-light-palette-orange)] text-[32px] font-semibold">4.</span>
                <div>
                  <p class="text-h4 font-semibold text-[var(--color-violet-950)]">C’est rapide et facile</p>
                  <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Le CTS se déplace sur votre lieu de travail pour réduire encore plus vos désagréments.</p>
                </div>
              </div>
            </div>

            <div class="space-y-3">
              <div class="flex items-start gap-4">
                <span class="text-[var(--color-light-palette-orange)] text-[32px] font-semibold">5.</span>
                <div>
                  <p class="text-h4 font-semibold text-[var(--color-violet-950)]">Vous vous sentirez bien</p>
                  <p class="mt-2 text-regular text-[var(--color-texte-primary-dark)]">Une bonne action procure un sentiment de satisfaction et de bien-être.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="mt-16 rounded-[32px] border border-[var(--color-violet-100)] bg-[var(--color-beige-50)] p-8 shadow-[0_24px_60px_rgba(104,23,100,0.08)] lg:p-10">
        <div class="text-center">
          <h2 class="text-h2 font-semibold text-[var(--color-violet-900)]">État actuel des réserves de sang à Genève</h2>
          <p class="mt-3 text-regular text-[var(--color-texte-primary-dark)]">Aujourd’hui, certains groupes sanguins nécessitent une attention particulière.</p>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <div v-for="type in bloodTypes" :key="type.value" class="rounded-[28px] bg-white p-5 text-center shadow-[0_10px_30px_rgba(104,23,100,0.08)]">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-[var(--color-violet-50)]">
              <span class="text-h4 font-bold text-[var(--color-rouge-600)]">{{ type.label }}</span>
            </div>
            <img src="sangAneg.png" alt="Groupe sanguin {{ type.label }}" class="mx-auto mb-4 h-28 w-28 object-cover" />
            <p class="text-regular font-semibold text-[var(--color-violet-900)]">{{ type.description }}</p>
          </div>
        </div>

        <div class="mt-8 rounded-[28px] bg-white p-6 shadow-[0_10px_30px_rgba(104,23,100,0.08)]">
          <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="flex items-center gap-3">
              <span class="h-3 w-3 rounded-full bg-[var(--color-vert-500)]"></span>
              <span class="text-small text-[var(--color-texte-primary-dark)]">Élevé — les stocks sont bien alimentés</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="h-3 w-3 rounded-full bg-[var(--color-vert-300)]"></span>
              <span class="text-small text-[var(--color-texte-primary-dark)]">Normal — les stocks suffisent pour plusieurs jours</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="h-3 w-3 rounded-full bg-[var(--color-light-palette-orange)]"></span>
              <span class="text-small text-[var(--color-texte-primary-dark)]">Bas — les stocks diminuent</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="h-3 w-3 rounded-full bg-[var(--color-rouge-400)]"></span>
              <span class="text-small text-[var(--color-texte-primary-dark)]">Critique — les stocks sont très limités</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="h-3 w-3 rounded-full bg-[var(--color-rouge-600)]"></span>
              <span class="text-small text-[var(--color-texte-primary-dark)]">Dangereusement bas — besoins urgents</span>
            </div>
          </div>
        </div>
      </section>

      <section class="mt-16 rounded-[32px] border border-[var(--color-violet-100)] bg-white p-8 shadow-[0_24px_60px_rgba(104,23,100,0.08)] lg:p-10">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
          <div class="rounded-[28px] bg-[var(--color-beige-50)] p-8 shadow-[0_10px_30px_rgba(104,23,100,0.08)]">
            <img src="composition_petites_gouttes.png" alt="" class="w-full rounded-[24px] object-cover" />
          </div>
          <div>
            <h3 class="text-h4 font-semibold text-[var(--color-violet-900)]">Qui peut donner son sang ?</h3>
            <p class="mt-4 text-regular text-[var(--color-texte-primary-dark)]">Âge, santé, voyages récents, traitements… quelques critères peuvent influencer votre éligibilité. Faites une vérification rapide avant votre inscription.</p>
            <a href="#/inscription" class="mt-6 inline-flex rounded-full bg-[var(--color-button-primary)] px-8 py-3 text-regular font-semibold text-[var(--color-button-secondary)] transition hover:bg-[var(--color-violet-800)]">Faire le test d’éligibilité</a>
          </div>
        </div>
      </section>

      <div class="mt-16 rounded-[32px] border border-[var(--color-violet-100)] bg-[var(--color-beige-50)] p-8 text-center shadow-[0_24px_60px_rgba(104,23,100,0.08)]">
        <p class="text-h3 font-semibold text-[var(--color-violet-900)]">Je veux donner mon sang</p>
        <a href="#/inscription" class="mt-6 inline-flex rounded-full bg-[var(--color-button-primary)] px-10 py-3 text-regular font-semibold text-[var(--color-button-secondary)] transition hover:bg-[var(--color-violet-800)]">Réservez votre place</a>
      </div>
    </main>
  </div>
</template>

<script setup>
const bloodTypes = [
  { label: 'O+', value: 'O+', level: 85, barColor: 'var(--color-vert-500)', description: 'Élevé' },
  { label: 'A+', value: 'A+', level: 60, barColor: 'var(--color-vert-500)', description: 'Normal' },
  { label: 'AB+', value: 'AB+', level: 35, barColor: 'var(--color-light-palette-orange)', description: 'Bas' },
  { label: 'B+', value: 'B+', level: 20, barColor: 'var(--color-light-palette-orange)', description: 'Bas' },
  { label: 'O-', value: 'O-', level: 75, barColor: 'var(--color-vert-500)', description: 'Normal' },
  { label: 'A-', value: 'A-', level: 45, barColor: 'var(--color-light-palette-orange)', description: 'Bas' },
  { label: 'AB-', value: 'AB-', level: 15, barColor: 'var(--color-rouge-400)', description: 'Critique' },
  { label: 'B-', value: 'B-', level: 10, barColor: 'var(--color-rouge-600)', description: 'Dangereusement bas' },
]
</script>
