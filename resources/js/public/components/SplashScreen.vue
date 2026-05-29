<script setup>
import { ref } from 'vue'

const visible = ref(sessionStorage.getItem('splash_seen') !== 'true')

function enterSite() {
  sessionStorage.setItem('splash_seen', 'true')
  visible.value = false
}
</script>

<template>
  <div v-if="visible"
       class="fixed inset-0 z-50 flex items-center justify-center bg-beige-50 px-4">
    <div class="flex w-full max-w-2xl flex-col items-center gap-12 py-10 text-center sm:gap-16">

      <!-- H1 -->
      <h1 class="font-sans text-h3 text-(--color-texte-primary-dark) sm:text-h1">
        Ce site est un projet d'étudiants-tes de la HEIG-VD
      </h1>

      <!-- Deux colonnes -->
      <div class="flex w-full flex-col items-center justify-center gap-10 sm:flex-row sm:gap-20">

        <!-- Colonne HUG -->
        <div class="flex flex-col items-center gap-5">
          <img
            :src="'/images/logo-hug.png'"
            alt="HUG — Hôpitaux Universitaires de Genève"
            class="h-24 w-auto object-contain"
          />
          <h2 class="font-sans text-h5 sm:text-h2">
            <a
              href="https://www.hug.ch/"
              target="_blank"
              rel="noopener noreferrer"
              class="text-(--color-texte-secondary) transition-colors hover:text-violet-500 underline"
            >
              Vers le site des HUG
            </a>
          </h2>
        </div>

        <!-- Colonne Notre site -->
        <div class="flex flex-col items-center gap-5">
          <img
            :src="'/images/hello.png'"
            alt=""
            class="h-24 w-auto object-contain"
          />
          <h2 class="font-sans text-h5 sm:text-h2">
            <button
              type="button"
              @click="enterSite"
              class="cursor-pointer text-(--color-texte-secondary) transition-colors hover:text-violet-500 underline"
            >
              Vers notre site
            </button>
          </h2>
        </div>

      </div>

    </div>
  </div>
</template>
