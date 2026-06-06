<script setup>
defineProps({
    show: { type: Boolean, default: false },
    mobile: { type: Boolean, default: false },
    preventionSlug: { type: String, default: null },
});

const emit = defineEmits(["close"]);
function close() {
    emit("close");
}
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-[60] flex items-center justify-center bg-black/20 p-4 backdrop-blur-md"
                @click.self="close"
            >
                <div v-if="mobile" class="relative w-full max-w-[360px]">
                    <img
                        :src="'/images/cobrand/quizz/overlay_mobile.svg'"
                        alt=""
                        class="block w-full select-none drop-shadow-xl"
                    />

                    <button
                        type="button"
                        aria-label="Fermer"
                        class="absolute right-[5%] top-[3%] flex h-9 w-9 items-center justify-center text-[#c9a0e0] transition-colors hover:text-[#b07fd0]"
                        @click="close"
                    >
                        <svg
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                        >
                            <path d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>

                    <div
                        class="absolute inset-x-[9%] bottom-[5%] top-[33%] flex flex-col items-center text-center"
                    >
                        <h2
                            class="text-h5 font-bold leading-tight text-violet-950"
                        >
                            Désolé, vous n'êtes pas éligible pour donner votre
                            sang
                        </h2>

                        <a
                            :href="preventionSlug ? `#/prevention#${preventionSlug}` : '#/prevention'"
                            class="mt-5 rounded-full bg-white px-5 py-2.5 text-small font-semibold text-violet-900 shadow-sm transition-colors hover:bg-white/90"
                            @click="close"
                        >
                            Voir pourquoi je ne suis pas éligible
                        </a>
                        <a
                            href="#/accueil"
                            class="mt-3 text-small text-violet-400 transition-colors hover:text-violet-600"
                            @click="close"
                        >
                            Page d'accueil
                        </a>

                        <div class="mt-auto pt-4">
                            <svg
                                viewBox="705 115 240 95"
                                class="h-12 w-auto"
                                fill="none"
                            >
                                <path
                                    d="M826.57,133.58c.66-1.09,9.55-15.31,24.13-15.24,12.75.07,22.27,11.03,25.14,19.05,6.59,18.41-12.72,46.88-49.27,69.33-35.56-19.7-55.21-46.86-49.78-66.79,2.08-7.65,9.38-19.81,21.84-21.59,10.29-1.47,21.24,4.56,27.94,15.24Z"
                                    fill="#e5afeb"
                                />
                                <g
                                    stroke="#e5afeb"
                                    stroke-width="8"
                                    stroke-linecap="round"
                                >
                                    <line
                                        x1="713.56"
                                        y1="125.96"
                                        x2="732.61"
                                        y2="139.16"
                                    />
                                    <line
                                        x1="920.42"
                                        y1="169.22"
                                        x2="939.47"
                                        y2="182.42"
                                    />
                                    <line
                                        x1="713.56"
                                        y1="182.42"
                                        x2="732.61"
                                        y2="169.22"
                                    />
                                    <line
                                        x1="920.42"
                                        y1="139.16"
                                        x2="939.47"
                                        y2="125.96"
                                    />
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>

                <div v-else class="relative w-full max-w-[900px]">
                    <img
                        :src="'/images/cobrand/quizz/overlay.svg'"
                        alt=""
                        class="block w-full select-none drop-shadow-2xl"
                    />

                    <button
                        type="button"
                        aria-label="Fermer"
                        class="absolute right-[3%] top-[7%] flex h-10 w-10 items-center justify-center text-[#c9a0e0] transition-colors hover:text-[#b07fd0]"
                        @click="close"
                    >
                        <svg
                            class="h-7 w-7"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                        >
                            <path d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>

                    <div
                        class="absolute bottom-[10%] left-[42%] right-[4%] top-[45%] flex flex-col items-center justify-center text-center"
                    >
                        <h2
                            class="text-h4 font-bold leading-tight text-violet-950"
                        >
                            Désolé, vous n'êtes pas éligible pour donner votre
                            sang
                        </h2>

                        <a
                            :href="preventionSlug ? `#/prevention#${preventionSlug}` : '#/prevention'"
                            class="mt-5 rounded-full bg-white px-6 py-3 text-regular font-semibold text-violet-900 shadow-sm transition-colors hover:bg-white/90"
                            @click="close"
                        >
                            Voir pourquoi je ne suis pas éligible
                        </a>
                        <a
                            href="#/accueil"
                            class="mt-3 text-small text-violet-400 transition-colors hover:text-violet-600"
                            @click="close"
                        >
                            Page d'accueil
                        </a>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
