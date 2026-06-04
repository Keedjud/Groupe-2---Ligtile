import { ref, computed } from "vue";
import { quizQuestions } from "../constants/quizQuestions";

const total = quizQuestions.length;

const statuses = ref(
    quizQuestions.map((_, i) => (i === 0 ? "waiting" : "sleeping")),
);
const answers = ref(quizQuestions.map(() => null));

export function useQuizStore() {
    function answer(index, value) {
        if (statuses.value[index] === "sleeping") return;

        const question = quizQuestions[index];
        const isGood = value === question.goodAnswer;

        statuses.value[index] = isGood ? "good" : "sad";
        answers.value[index] = value;

        const next = index + 1;
        if (
            next < quizQuestions.length &&
            statuses.value[next] === "sleeping"
        ) {
            statuses.value[next] = "waiting";
        }
    }

    function skipQuestion(index) {
        if (statuses.value[index] !== "waiting") return;
        const next = index + 1;
        if (
            next < quizQuestions.length &&
            statuses.value[next] === "sleeping"
        ) {
            statuses.value[next] = "waiting";
        }
        statuses.value[index] = "sleeping";
    }

    function reset() {
        statuses.value = quizQuestions.map((_, i) =>
            i === 0 ? "waiting" : "sleeping",
        );
        answers.value = quizQuestions.map(() => null);
    }

    const isAnswered = (i) =>
        statuses.value[i] === "good" || statuses.value[i] === "sad";

    const mandatoryTotal = quizQuestions.filter((q) => q.mandatory).length;
    const mandatoryGoodCount = computed(
        () =>
            quizQuestions.filter(
                (q, i) => q.mandatory && statuses.value[i] === "good",
            ).length,
    );
    const fillRatio = computed(() =>
        mandatoryTotal === 0 ? 0 : mandatoryGoodCount.value / mandatoryTotal,
    );

    const allMandatoryAnswered = computed(() =>
        quizQuestions.every((q, i) => !q.mandatory || isAnswered(i)),
    );

    const hasMandatoryNegative = computed(() =>
        quizQuestions.some(
            (q, i) => q.mandatory && statuses.value[i] === "sad",
        ),
    );

    const eligible = computed(
        () => allMandatoryAnswered.value && !hasMandatoryNegative.value,
    );

    const canSkip = computed(() => allMandatoryAnswered.value);

    return {
        questions: quizQuestions,
        total,
        statuses,
        answers,
        answer,
        skipQuestion,
        reset,
        fillRatio,
        allMandatoryAnswered,
        hasMandatoryNegative,
        eligible,
        canSkip,
    };
}
