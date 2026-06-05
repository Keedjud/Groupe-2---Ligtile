<script setup>
import { useCobrandSession } from "../composables/useCobrandSession";

const { sessionError } = useCobrandSession();
</script>

<template>
    <div class="mx-auto max-w-3xl px-6 py-24 text-center">
        <div
            v-if="sessionError"
            class="mx-auto max-w-md rounded-lg border border-red-200 bg-red-50 px-6 py-5 text-red-800"
        >
            <p class="font-semibold">Erreur de chargement</p>
            <p class="mt-1 text-small">
                Impossible d'initialiser la session. Veuillez vérifier le
                lien ou réessayer plus tard.
            </p>
        </div>

        <template v-else>
            <h1 class="text-h2 font-bold text-violet-950">Accueil</h1>
            <p class="mt-4 text-violet-900">
                Page en construction.
                <a
                    href="#/quiz"
                    class="font-medium text-violet-900 underline hover:text-violet-700"
                    >Aller au quiz</a
                >
            </p>
        </template>
    </div>
</template>
