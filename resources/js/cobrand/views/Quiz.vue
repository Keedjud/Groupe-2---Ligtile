<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted, watch } from "vue";
import { useMediaQuery } from "@/composables/useMediaQuery";
import { useQuizStore } from "../composables/useQuizStore";
import { useCobrandSession } from "../composables/useCobrandSession";
import QuizTile from "../components/QuizTile.vue";
import InscriptionButton from "../components/InscriptionButton.vue";
import EligibilityOverlay from "../components/EligibilityOverlay.vue";

const {
    questions,
    statuses,
    answers,
    start,
    answer,
    skipQuestion,
    skipForm,
    reset,
    fillRatio,
    allMandatoryAnswered,
    hasMandatoryNegative,
    eligible,
    canSkip,
} = useQuizStore();

const { onedocUrl, trackQuiz } = useCobrandSession();

const firstMandatoryNegativeSlug = computed(() =>
    questions.find((question, index) => question.mandatory && statuses.value[index] === "sad")
        ?.preventionSlug ?? null,
);

let footerObserver = null;

onMounted(() => {
    reset();
    start();
    nextTick(() => {
        const footer = document.querySelector("footer");
        if (footer) {
            footerObserver = new IntersectionObserver(
                ([entry]) => { footerVisible.value = entry.isIntersecting; },
                { threshold: 0 },
            );
            footerObserver.observe(footer);
        }
    });
});

onUnmounted(() => {
    footerObserver?.disconnect();
});

const inscriptionRef = ref(null);
const rowRefs = ref([]);
const showOverlay = ref(false);
const footerVisible = ref(false);

const isMobile = useMediaQuery("(max-width: 1023px)");

function side(i) {
    if (isMobile.value) return "right";
    return i % 2 === 0 ? "left" : "right";
}

function scrollToNext(i) {
    const next = i + 1;
    nextTick(() => {
        const target =
            next < questions.length
                ? rowRefs.value[next]
                : inscriptionRef.value;
        target?.scrollIntoView({ behavior: "smooth", block: "center" });
    });
}

watch(hasMandatoryNegative, (isNegative, wasNegative) => {
    if (isNegative && !wasNegative) showOverlay.value = true;
});

function onAnswer(i, value) {
    answer(i, value);
    if (statuses.value[i] === "good") scrollToNext(i);
}

function onSkip(i) {
    skipQuestion(i);
    scrollToNext(i);
}

function dropType(i) {
    const s = statuses.value[i];
    if (s === "good") return "falling";
    if (s === "sad") return "incorrect";
    return "unanswered";
}

const hasOptionalNegative = computed(() =>
    questions.some((q, i) => !q.mandatory && statuses.value[i] === "sad"),
);

const message = computed(() => {
    if (hasMandatoryNegative.value) {
        return "Désolé, vous n'êtes pas éligible.";
    }
    if (!allMandatoryAnswered.value) {
        return "Répondez aux questions obligatoires pour pouvoir vous inscrire.";
    }
    if (hasOptionalNegative.value) {
        return "Vous pouvez vous inscrire mais votre situation n'est pas idéale. Veuillez idéalement contacter le CTS.";
    }
    return "Vous pouvez vous inscrire.";
});

function register() {
    if (!eligible.value || !onedocUrl.value) return;
    trackQuiz({ event_type: "quiz_completed" });
    window.open(onedocUrl.value, "_blank", "noopener,noreferrer");
}

function skip() {
    if (!canSkip.value) return;
    skipForm();
    inscriptionRef.value?.scrollIntoView({
        behavior: "smooth",
        block: "center",
    });
}
</script>

<template>
    <template v-if="questions.length">
        <div
            v-if="isMobile"
            class="mx-auto w-full max-w-[520px] px-3 pb-24 pt-28"
        >
            <div class="relative">
                <div
                    class="absolute bottom-0 left-[18px] top-0 w-[4px] -translate-x-1/2 bg-black"
                ></div>

                <div class="flex flex-col gap-9">
                    <div
                        v-for="(q, i) in questions"
                        :key="q.slug"
                        :ref="(el) => (rowRefs[i] = el)"
                        class="relative"
                    >
                        <div
                            class="absolute left-[18px] top-[53px] h-0 -translate-y-1/2 border-t-[3px] border-dashed border-black"
                            style="width: calc(50% - 125px - 18px)"
                        ></div>

                        <div
                            class="absolute left-[18px] top-[53px] z-10 -translate-x-1/2 -translate-y-1/2"
                        >
                            <img
                                v-if="dropType(i) === 'unanswered'"
                                :src="'/images/cobrand/quizz/goutte-rouge.svg'"
                                class="h-9 w-auto"
                                alt=""
                            />
                            <img
                                v-else-if="dropType(i) === 'incorrect'"
                                :src="'/images/cobrand/quizz/goutte-rouge.svg'"
                                class="h-9 w-auto"
                                style="filter: brightness(0)"
                                alt=""
                            />
                            <img
                                v-else
                                :src="'/images/cobrand/quizz/goutte-rouge.svg'"
                                class="drop-fall h-9 w-auto"
                                alt=""
                            />
                        </div>

                        <div class="mx-auto max-w-[250px]">
                            <QuizTile
                                :question="q"
                                :status="statuses[i]"
                                :answer="answers[i]"
                                :has-mandatory-negative="hasMandatoryNegative"
                                mobile
                                @answer="(v) => onAnswer(i, v)"
                                @skip="onSkip(i)"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div
                ref="inscriptionRef"
                class="relative z-10 mt-12 flex flex-col items-center gap-4"
            >
                <p
                    class="max-w-[400px] text-center text-regular text-violet-950"
                >
                    {{ message }}
                </p>
                <InscriptionButton
                    :ratio="fillRatio"
                    :enabled="eligible"
                    @click="register"
                />
            </div>

            <Teleport to="body">
                <button
                    v-show="!footerVisible"
                    type="button"
                    :disabled="!canSkip"
                    @click="skip"
                    class="fixed bottom-8 right-4 z-50 rounded-full px-5 py-2.5 text-small shadow-lg transition-colors disabled:cursor-not-allowed"
                    :class="
                        canSkip
                            ? 'cursor-pointer bg-violet-900 text-white hover:bg-violet-800'
                            : 'bg-violet-200 text-white/70'
                    "
                >
                    Passer ›
                </button>
            </Teleport>
        </div>

        <div v-else class="relative mx-auto w-full max-w-[1240px] px-8 pb-24 pt-[280px]">
            <div class="relative">
                <div
                    class="absolute bottom-0 left-1/2 top-0 w-[5px] -translate-x-1/2 bg-black"
                ></div>

                <div class="flex flex-col gap-28">
                    <div
                        v-for="(q, i) in questions"
                        :key="q.slug"
                        :ref="(el) => (rowRefs[i] = el)"
                        class="relative h-[210px]"
                    >
                        <div
                            class="absolute top-1/2 -translate-y-1/2 flex w-[calc(50%-80px)]"
                            :class="
                                side(i) === 'left'
                                    ? 'right-[calc(50%+80px)] justify-end'
                                    : 'left-[calc(50%+80px)] justify-start'
                            "
                        >
                            <QuizTile
                                :question="q"
                                :status="statuses[i]"
                                :answer="answers[i]"
                                :side="side(i)"
                                :has-mandatory-negative="hasMandatoryNegative"
                                @answer="(v) => onAnswer(i, v)"
                                @skip="onSkip(i)"
                            />
                        </div>

                        <div
                            class="absolute top-1/2 h-0 w-[80px] border-t-[3px] border-dashed border-black"
                            :class="
                                side(i) === 'left'
                                    ? 'right-1/2'
                                    : 'left-1/2'
                            "
                        ></div>

                        <div
                            class="absolute left-1/2 top-1/2 z-10 -translate-x-1/2 -translate-y-1/2"
                        >
                            <img
                                v-if="dropType(i) === 'unanswered'"
                                :src="'/images/cobrand/quizz/goutte-rouge.svg'"
                                class="h-12 w-auto"
                                alt=""
                            />
                            <img
                                v-else-if="dropType(i) === 'incorrect'"
                                :src="'/images/cobrand/quizz/goutte-rouge.svg'"
                                class="h-12 w-auto"
                                style="filter: brightness(0)"
                                alt=""
                            />
                            <img
                                v-else
                                :src="'/images/cobrand/quizz/goutte-rouge.svg'"
                                class="drop-fall h-12 w-auto"
                                alt=""
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div
                ref="inscriptionRef"
                class="relative z-10 mt-12 flex flex-col items-center gap-4"
            >
                <p
                    class="max-w-[440px] text-center text-regular text-violet-950"
                >
                    {{ message }}
                </p>
                <InscriptionButton
                    :ratio="fillRatio"
                    :enabled="eligible"
                    @click="register"
                />
            </div>

            <button
                v-show="!footerVisible"
                type="button"
                :disabled="!canSkip"
                @click="skip"
                class="fixed bottom-8 right-8 z-40 rounded-full px-12 py-3 text-regular transition-colors disabled:cursor-not-allowed"
                :class="
                    canSkip
                        ? 'cursor-pointer bg-violet-900 text-white hover:bg-violet-800'
                        : 'bg-violet-200 text-white/70'
                "
            >
                Passer ›
            </button>
        </div>
    </template>

    <EligibilityOverlay
        :show="showOverlay"
        :mobile="isMobile"
        :prevention-slug="firstMandatoryNegativeSlug"
        @close="showOverlay = false"
    />
</template>

<style scoped>
.drop-fall {
    animation: dropfall 1s ease-in forwards;
}
@keyframes dropfall {
    0% {
        transform: translateY(0);
        opacity: 1;
    }
    100% {
        transform: translateY(130px);
        opacity: 0;
    }
}
</style>
