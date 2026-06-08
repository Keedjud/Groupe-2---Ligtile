<script setup>
import { computed } from "vue";
import AnswerButton from "./AnswerButton.vue";
import ThoughtBubble from "./ThoughtBubble.vue";

const props = defineProps({
    question: { type: Object, required: true },
    status: { type: String, required: true },
    side: { type: String, default: "left" },
    answer: { type: String, default: null },
    mobile: { type: Boolean, default: false },
});

const emit = defineEmits(["answer", "skip"]);

const IMG = {
    sleeping: "/images/cobrand/quizz/sleeping.svg",
    waiting: "/images/cobrand/quizz/waiting.svg",
    skipped: "/images/cobrand/quizz/waiting.svg",
    good: "/images/cobrand/quizz/good.svg",
    sad: "/images/cobrand/quizz/sad.svg",
};

const IMG_MOBILE = {
    sleeping: "/images/cobrand/quizz/sleeping-mobile.svg",
    waiting: "/images/cobrand/quizz/waiting-mobile.svg",
    skipped: "/images/cobrand/quizz/waiting-mobile.svg",
    good: "/images/cobrand/quizz/good-mobile.svg",
    sad: "/images/cobrand/quizz/sad-mobile.svg",
};

const BAND_COLOR = {
    sleeping: "#f2a09d",
    waiting: "#d41f1f",
    skipped: "#d41f1f",
    good: "#d41f1f",
    sad: "#f2a09d",
};
const bandColor = computed(() => BAND_COLOR[props.status]);

const answerable = computed(() => props.status !== "sleeping");

const canSkipQuestion = computed(
    () => !props.question.mandatory && props.status === "waiting",
);

const sadNote = computed(() =>
    props.question.mandatory
        ? "Vous n'êtes pas éligible."
        : "Vous êtes toujours éligible.",
);

function btnState(variant) {
    if (props.status === "sleeping") return "disabled";
    if (props.status === "waiting" || props.status === "skipped") return "active";
    return props.answer === variant ? "active" : "muted";
}

function choose(variant) {
    if (answerable.value) emit("answer", variant);
}
</script>

<template>
    <div
        v-if="mobile"
        class="w-full overflow-hidden rounded-[26px] bg-[#f7e8fb] shadow-[0_4px_8px_rgba(0,0,0,0.06)]"
    >
        <div
            class="aspect-[481/205] w-full overflow-hidden"
            :style="{ backgroundColor: bandColor }"
        >
            <img
                :src="IMG_MOBILE[status]"
                alt=""
                class="-mx-px block w-[calc(100%+2px)] max-w-none select-none"
            />
        </div>

        <div
            class="flex flex-col items-center gap-3 px-[9%] pb-5 pt-3 text-center"
        >
            <p class="text-[12px] font-medium leading-snug text-violet-950">
                {{ question.text }}
            </p>

            <div class="flex justify-center gap-2">
                <AnswerButton
                    label="Non"
                    variant="non"
                    :state="btnState('non')"
                    @click="choose('non')"
                />
                <AnswerButton
                    label="Oui"
                    variant="oui"
                    :state="btnState('oui')"
                    @click="choose('oui')"
                />
            </div>

            <button
                v-if="canSkipQuestion"
                type="button"
                class="text-small text-violet-700 underline underline-offset-2 transition-colors hover:text-violet-900"
                @click="emit('skip')"
            >
                Passer cette question
            </button>

            <div
                v-if="status === 'sad'"
                class="mt-1 w-full rounded-2xl bg-[#f2a09d] p-4 text-left"
            >
                <p class="text-[12px] leading-snug text-violet-950">
                    {{ question.reason }}
                </p>
                <p
                    class="mt-1.5 text-[12px] font-semibold leading-snug text-violet-950"
                >
                    {{ sadNote }}
                </p>
                <a
                    :href="question.preventionSlug ? `#/prevention#${question.preventionSlug}` : '#/prevention'"
                    class="mt-3 block rounded-full bg-white px-4 py-2.5 text-center text-[12px] font-semibold text-violet-950 shadow-sm transition-colors hover:bg-white/90"
                >
                    En savoir plus
                </a>
            </div>
        </div>
    </div>

    <div v-else class="relative w-full lg:max-w-[520px]">
        <img
            :src="IMG[status]"
            alt=""
            class="block w-full select-none"
            :style="side === 'right' ? 'transform: scaleX(-1);' : ''"
        />

        <ThoughtBubble
            v-if="status === 'sad'"
            :reason="question.reason"
            :mandatory="question.mandatory"
            :prevention-slug="question.preventionSlug"
            :side="side"
            class="absolute bottom-full z-20 mb-1"
            :class="side === 'right' ? 'right-[90px]' : 'left-[90px]'"
        />

        <div
            class="absolute inset-y-0 flex flex-col items-center justify-center gap-3 px-2 text-center"
            :style="
                side === 'right'
                    ? 'left: 2%; right: 40%;'
                    : 'left: 40%; right: 2%;'
            "
        >
            <p class="text-small leading-snug text-violet-950">
                {{ question.text }}
            </p>
            <div class="flex justify-center gap-3">
                <AnswerButton
                    label="Non"
                    variant="non"
                    :state="btnState('non')"
                    @click="choose('non')"
                />
                <AnswerButton
                    label="Oui"
                    variant="oui"
                    :state="btnState('oui')"
                    @click="choose('oui')"
                />
            </div>
            <button
                v-if="canSkipQuestion"
                type="button"
                class="text-small text-violet-700 underline underline-offset-2 transition-colors hover:text-violet-900"
                @click="emit('skip')"
            >
                Passer cette question
            </button>
        </div>
    </div>
</template>
