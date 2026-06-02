<!-- Page de connexion au dashboard -->
<script setup>
import { ref } from 'vue'
import FictifBanner     from '../components/FictifBanner.vue'
import { useSessionAuth } from '../composables/useSessionAuth.js'

const props = defineProps({
  allerVers: { type: Function, required: true },
})

const { connecter, chargementAuth } = useSessionAuth()

const champEmail    = ref('')
const champMotDePasse = ref('')
const messageErreur = ref('')

async function soumettre() {
  messageErreur.value = ''
  if (!champEmail.value || !champMotDePasse.value) {
    messageErreur.value = 'Veuillez remplir tous les champs.'
    return
  }
  const resultat = await connecter(champEmail.value, champMotDePasse.value)
  if (resultat.succes) {
    props.allerVers('#/collectes')
  } else {
    messageErreur.value = resultat.message ?? 'Identifiants invalides.'
  }
}
</script>

<template>
  <div class="flex min-h-screen flex-col bg-beige-50 font-sans antialiased">

    <!-- Bandeau fictif en haut -->
    <FictifBanner />

    <!-- Carte de connexion centrée -->
    <div class="flex flex-1 items-center justify-center px-4 py-10">
      <div class="w-full max-w-md rounded-[20px] bg-form-bg p-8 shadow-[0_4px_16px_rgba(104,23,100,0.12)]">

        <!-- En-tête carte -->
        <div class="mb-6 flex flex-col items-center gap-1">
          <!-- Logo texte Trophée + point rouge -->
          <div class="flex items-center gap-1">
            <span class="font-sans text-h2 font-bold text-violet-900">Trophée</span>
            <span class="h-2 w-2 rounded-full bg-rouge-500" />
          </div>
          <span class="font-sans text-small font-semibold tracking-widest text-texte-secondary uppercase">
            Label CTS
          </span>
        </div>

        <h1 class="mb-1 text-center font-sans text-h1 font-bold text-violet-900">Connexion</h1>
        <p class="mb-8 text-center font-sans text-small text-violet-700">
          Espace d'administration du label
        </p>

        <form @submit.prevent="soumettre" class="flex flex-col gap-5">
          <!-- Identifiant -->
          <div class="flex flex-col gap-1">
            <label class="font-sans text-small font-semibold text-texte-secondary" for="email">
              Identifiant
            </label>
            <input
              id="email"
              v-model="champEmail"
              type="email"
              placeholder="prenom.nom@trophee.ch"
              autocomplete="email"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-regular text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.15)] outline-none focus:ring-2 focus:ring-violet-400"
            />
          </div>

          <!-- Mot de passe -->
          <div class="flex flex-col gap-1">
            <label class="font-sans text-small font-semibold text-texte-secondary" for="mdp">
              Mot de passe
            </label>
            <input
              id="mdp"
              v-model="champMotDePasse"
              type="password"
              placeholder="Votre mot de passe"
              autocomplete="current-password"
              class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-regular text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.15)] outline-none focus:ring-2 focus:ring-violet-400"
            />
          </div>

          <!-- Message d'erreur -->
          <p v-if="messageErreur" class="font-sans text-small text-rouge-500" role="alert">
            {{ messageErreur }}
          </p>

          <!-- Bouton connexion -->
          <button
            type="submit"
            :disabled="chargementAuth"
            class="mt-2 w-full rounded-[40px] bg-violet-900 py-3 font-sans text-regular text-white transition-opacity hover:opacity-90 disabled:cursor-wait disabled:opacity-60"
          >
            {{ chargementAuth ? 'Connexion…' : 'Se connecter' }}
          </button>
        </form>

      </div>
    </div>

    <!-- Notice bas -->
    <footer class="w-full bg-rouge-500 p-2.5 text-center font-sans text-small font-bold text-light-palette-black">
      Ce site est la réalisation d'un projet d'étudiant·es de la HEIG-VD en collaboration avec les HUG.
    </footer>

  </div>
</template>
