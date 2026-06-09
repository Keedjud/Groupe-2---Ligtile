import { ref, computed, readonly } from "vue";
import { quizQuestions } from "../constants/quizQuestions";
import { useCobrandSession } from "./useCobrandSession";

const { trackQuiz } = useCobrandSession();

const statuses = ref(
    quizQuestions.map((_, i) => (i === 0 ? "waiting" : "sleeping")),
);
const answers = ref(quizQuestions.map(() => null));
const processed = ref(quizQuestions.map(() => false));

let firedStarted = false;
let firedP1Eliminated = false;
let firedP1Completed = false;
let firedP2Completed = false;

const partOf = (q) => (q.mandatory ? 1 : 2);

const allMandatoryGood = () =>
    quizQuestions.every((q, i) => !q.mandatory || statuses.value[i] === "good");

const allOptionalProcessed = () =>
    quizQuestions.every((q, i) => q.mandatory || processed.value[i]);

function checkProgress() {
    if (!firedP1Completed && allMandatoryGood()) {
        firedP1Completed = true;
        trackQuiz({ event_type: "p1_completed", part: 1 });
    }
    if (firedP1Completed && !firedP2Completed && allOptionalProcessed()) {
        firedP2Completed = true;
        trackQuiz({ event_type: "p2_completed", part: 2 });
    }
}

export function useQuizStore() {
    function start() {
        if (firedStarted) return;
        firedStarted = true;
        trackQuiz({ event_type: "quiz_started" });
    }

    function answer(index, value) {
        if (statuses.value[index] === "sleeping") return;
        if (answers.value[index] === value) return;

        const question = quizQuestions[index];
        const isGood = value === question.goodAnswer;

        statuses.value[index] = isGood ? "good" : "sad";
        answers.value[index] = value;
        processed.value[index] = true;

        trackQuiz({
            event_type: "question_answered",
            part: partOf(question),
            question_slug: question.slug,
            answer_result: isGood ? "correct" : "incorrect",
        });

        if (question.mandatory && !isGood && !firedP1Eliminated) {
            firedP1Eliminated = true;
            trackQuiz({ event_type: "p1_eliminated", part: 1 });
        }

        const next = index + 1;
        if (next < quizQuestions.length && statuses.value[next] === "sleeping") {
            statuses.value[next] = "waiting";
        }

        checkProgress();
    }

    function skipQuestion(index) {
        if (statuses.value[index] !== "waiting") return;

        const question = quizQuestions[index];
        processed.value[index] = true;

        trackQuiz({
            event_type: "question_skipped",
            part: partOf(question),
            question_slug: question.slug,
        });

        const next = index + 1;
        if (next < quizQuestions.length && statuses.value[next] === "sleeping") {
            statuses.value[next] = "waiting";
        }
        statuses.value[index] = "skipped";

        checkProgress();
    }

    function skipForm() {
        const pending = quizQuestions.findIndex((_, i) => !processed.value[i]);
        trackQuiz({
            event_type: "form_skipped_from",
            part: 2,
            question_slug: pending >= 0 ? quizQuestions[pending].slug : null,
        });
    }

    function reset() {
        statuses.value = quizQuestions.map((_, i) =>
            i === 0 ? "waiting" : "sleeping",
        );
        answers.value = quizQuestions.map(() => null);
        processed.value = quizQuestions.map(() => false);
        firedStarted = false;
        firedP1Eliminated = false;
        firedP1Completed = false;
        firedP2Completed = false;
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
        quizQuestions.some((q, i) => q.mandatory && statuses.value[i] === "sad"),
    );

    const eligible = computed(
        () => allMandatoryAnswered.value && !hasMandatoryNegative.value,
    );

    const canSkip = computed(() => allMandatoryAnswered.value);

    return {
        questions: quizQuestions,
        statuses: readonly(statuses),
        answers: readonly(answers),
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
    };
}
