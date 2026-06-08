<script setup>
import { nextTick, onMounted, onUnmounted, ref } from "vue";
import { useCobrandSession } from "../composables/useCobrandSession";
import { preventionTopics } from "../constants/preventionTopics";
import BackgroundMotifs from "../components/BackgroundMotifs.vue";
import PreventionHeading from "../components/PreventionHeading.vue";
import PreventionGlyph from "../components/PreventionGlyph.vue";
import PreventionCard from "../components/PreventionCard.vue";

const ART = "/images/cobrand/prevention";

const figures = {
    impact: `${ART}/hi.png`,
    peur: `${ART}/peur-seringue.png`,
    pendant: `${ART}/doctor-1.png`,
    devient: `${ART}/poche-sang.svg`,
    eligible: `${ART}/why-ineligible.png`,
    apres: `${ART}/bye-bye.png`,
};

const cableFigures = [
    {
        src: figures.impact,
        alt: "Goutte de sang qui salue",
        x: 80,
        y: 9,
        originX: 0.5,
        originY: 0.5,
        width: "230px",
    },
    {
        src: figures.peur,
        alt: "Goutte de sang inquiète face à une seringue",
        x: 73,
        y: 34,
        originX: 0.38,
        originY: 0.5,
        width: "300px",
    },
    {
        src: figures.pendant,
        alt: "Goutte de sang en blouse de médecin",
        x: 78,
        y: 59,
        originX: 0.5,
        originY: 0.52,
        width: "210px",
    },
    {
        src: figures.devient,
        alt: "Poche de sang",
        x: 72,
        y: 84,
        originX: 0.5,
        originY: 0.22,
        width: "260px",
    },
];

const cablePath =
    "M80 9 C80 22 73 22 73 34 C73 47 78 47 78 59 C78 72 72 72 72 84 L72 92 C72 96 50 98 -12 98";

const impactText = `Chaque jour en Suisse, des poches de sang sont nécessaires pour soigner des patients après un accident, une opération, ou encore pour un traitement contre le cancer.

Aujourd'hui, aucune technologie ne remplace le don humain et le sang ne se conserve pas indéfiniment. Sans donneurs réguliers, il n'y a tout simplement pas de réserves.`;

const peurIntro =
    "C'est normal. La peur des aiguilles est l'une des plus courantes mais elle n'empêche pas de donner son sang.";

const peurDetail =
    "Ce que vous ressentez au moment de la piqûre ? Un léger pincement, qui dure à peine quelques secondes. Ensuite, vous ne sentez plus rien. Le don se fait en position allongée ou semi-allongée, en 8 à 10 minutes.";

const tips = [
    { icon: "drop", text: "Bien s'hydrater avant de venir" },
    {
        icon: "music",
        text: "Apporter de quoi se distraire (musique, téléphone)",
    },
    {
        icon: "chat",
        text: "Et surtout en parler à l'équipe médicale sur place.",
    },
];

const pendantText = `À votre arrivée, l'équipe médicale vous accueille et vous guide à travers quelques étapes simples : questionnaire, vérification de votre dossier, et un entretien confidentiel avec un-e infirmier-ère pour confirmer votre aptitude au don.

Vous vous installez ensuite pour le prélèvement, en position allongée ou semi-allongée. Profitez-en pour écouter de la musique ou simplement souffler, vous avez le temps.

Une collation vous attend à la fin. Vous repartez avec la satisfaction d'avoir fait quelque chose qui compte vraiment.`;

const devientText = `Après le prélèvement, votre sang est analysé, contrôlé et séparé en différents composants (globules rouges, plaquettes, plasma) afin de répondre à des besoins médicaux spécifiques.

Chaque composant peut être destiné à un patient différent, ce qui permet à un seul don d'aider plusieurs personnes. Tout au long du processus, votre don est entièrement traçable afin de garantir la sécurité transfusionnelle.

Vos données personnelles sont protégées et restent confidentielles. La sécurité du donneur comme celle du receveur est assurée à chaque étape du processus.`;

const eligibilityNotes = [
    {
        image: "/images/cobrand/prevention/icon-fleche.png",
        text: "Certaines contre-indications sont permanentes, d'autres sont simplement temporaires, liées à un état de santé passager, un voyage récent ou un traitement en cours.",
    },
    {
        image: "/images/cobrand/prevention/icon-sablier.png",
        text: "Découvrez les principales raisons qui peuvent empêcher un don, et comprenez pourquoi ces critères existent. Ils sont là pour protéger à la fois le donneur et le receveur.",
    },
];

const apresText = `Une fois le prélèvement terminé, vous restez quelques minutes en observation avant de rejoindre l'espace collation offerte par votre entreprise.

C'est un moment simple pour reprendre des forces avant de retourner à vos activités.

Dans les heures qui suivent, buvez davantage que d'habitude et évitez les efforts physiques intenses. Votre organisme reconstitue naturellement le volume sanguin prélevé en quelques heures, et les globules rouges en quelques semaines.

Si vous ressentez des vertiges ou une fatigue inhabituelle, asseyez-vous et hydratez-vous. Ces sensations sont passagères et sans danger. N'hésitez pas à en parler à l'équipe médicale sur place avant de partir.`;

const peachCircle =
    "background-color: color-mix(in srgb, var(--prevention-accent) 22%, white)";

function figureStyle(fig) {
    return {
        left: `${fig.x}%`,
        top: `${fig.y}%`,
        width: fig.width,
        transform: `translate(${-fig.originX * 100}%, ${-fig.originY * 100}%)`,
    };
}

const dropActive = `${ART}/goutte-rouge-slider.png`;
const dropInactive = `${ART}/goutte-grise-slider.png`;
const arrowIcon = `${ART}/icon-arrow-slider.svg`;

function arrowMaskStyle(flip) {
    return {
        maskImage: `url(${arrowIcon})`,
        WebkitMaskImage: `url(${arrowIcon})`,
        maskRepeat: "no-repeat",
        WebkitMaskRepeat: "no-repeat",
        maskSize: "contain",
        WebkitMaskSize: "contain",
        maskPosition: "center",
        WebkitMaskPosition: "center",
        transform: flip ? "scaleX(-1)" : "none",
    };
}

const topicsTrack = ref(null);
const topicPage = ref(0);
const topicPageCount = ref(1);

const prefersReducedMotion =
    window.matchMedia?.("(prefers-reduced-motion: reduce)").matches ?? false;

function scrollBehavior() {
    return prefersReducedMotion ? "auto" : "smooth";
}

let topicScrollTimer = null;
let programmaticScrolling = false;

function clearTopicScrollTimer() {
    if (topicScrollTimer) {
        clearInterval(topicScrollTimer);
        topicScrollTimer = null;
    }
}

function scrollToTopicFromHash() {
    clearTopicScrollTimer();

    const parts = window.location.hash.split('#');
    if (parts.length <= 2) return;

    const topicSlug = parts[2];
    let attempts = 0;
    programmaticScrolling = true;

    topicScrollTimer = setInterval(() => {
        const card = document.getElementById('topic-' + topicSlug);
        if (card && topicsTrack.value) {
            const trackRect = topicsTrack.value.getBoundingClientRect();
            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const targetY = trackRect.top + scrollTop - 100;

            if (Math.abs(scrollTop - targetY) > 50) {
                window.scrollTo({ top: targetY, behavior: scrollBehavior() });
            }

            const cardRect = card.getBoundingClientRect();
            const scrollLeft =
                cardRect.left -
                trackRect.left +
                topicsTrack.value.scrollLeft -
                (trackRect.width / 2) +
                (cardRect.width / 2);

            if (Math.abs(topicsTrack.value.scrollLeft - scrollLeft) > 10) {
                topicsTrack.value.scrollTo({ left: scrollLeft, behavior: scrollBehavior() });
            }
        }

        attempts++;
        if (attempts > 8) {
            clearTopicScrollTimer();
            setTimeout(() => { programmaticScrolling = false; }, prefersReducedMotion ? 0 : 600);
        }
    }, 100);
}

function updateTopicIndicator() {
    const track = topicsTrack.value;
    if (!track || track.clientWidth === 0) return;
    const count = Math.max(1, Math.ceil(track.scrollWidth / track.clientWidth));
    topicPageCount.value = count;
    const maxScroll = track.scrollWidth - track.clientWidth;
    const page =
        maxScroll > 0
            ? Math.round((track.scrollLeft / maxScroll) * (count - 1))
            : 0;
    topicPage.value = Math.min(count - 1, Math.max(0, page));
}

const TOPIC_SCROLL_DURATION = 280;
let topicScrollFrame = null;

function animateTopicScroll(track, to) {
    if (topicScrollFrame) cancelAnimationFrame(topicScrollFrame);

    const from = track.scrollLeft;
    const distance = to - from;
    const start = performance.now();

    function step(now) {
        const t = Math.min(1, (now - start) / TOPIC_SCROLL_DURATION);
        const eased = 1 - Math.pow(1 - t, 3);
        track.scrollLeft = from + distance * eased;
        if (t < 1) topicScrollFrame = requestAnimationFrame(step);
    }

    topicScrollFrame = requestAnimationFrame(step);
}

function goToTopicPage(page) {
    const track = topicsTrack.value;
    if (!track) return;
    const clamped = Math.max(0, Math.min(topicPageCount.value - 1, page));
    topicPage.value = clamped;
    const maxScroll = track.scrollWidth - track.clientWidth;
    const left =
        topicPageCount.value > 1
            ? (clamped / (topicPageCount.value - 1)) * maxScroll
            : 0;

    if (prefersReducedMotion) {
        track.scrollLeft = left;
        return;
    }
    animateTopicScroll(track, left);
}

function onTopicsKeydown(event) {
    if (event.key === "ArrowRight") {
        event.preventDefault();
        goToTopicPage(topicPage.value + 1);
    } else if (event.key === "ArrowLeft") {
        event.preventDefault();
        goToTopicPage(topicPage.value - 1);
    }
}

const WHEEL_SCROLL_FACTOR = 0.7;

function redirectWheel(event) {
    const track = topicsTrack.value;
    if (!track) return;

    const delta = event.deltaY || event.deltaX;
    if (delta === 0) return;

    const atStart = track.scrollLeft <= 0;
    const atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 1;

    if ((delta < 0 && atStart) || (delta > 0 && atEnd)) return;

    event.preventDefault();
    track.scrollBy({ left: delta * WHEEL_SCROLL_FACTOR, behavior: "auto" });
}

const { trackPage, trackPageBeacon } = useCobrandSession();

const engaged = ref(false);
let startTime = 0;
let exitSent = false;

function markEngaged() {
    engaged.value = true;
}

function onUserScroll() {
    if (programmaticScrolling) return;
    engaged.value = true;
    window.removeEventListener("scroll", onUserScroll);
}

function sendExit() {
    if (exitSent) return;
    exitSent = true;

    const durationSeconds = Math.round((Date.now() - startTime) / 1000);
    trackPageBeacon({
        event_type: "prevention_exited",
        engaged: engaged.value,
        time_on_page: durationSeconds,
    });
}

function onVisibilityChange() {
    if (document.visibilityState === "hidden") sendExit();
}

onMounted(() => {
    startTime = Date.now();
    trackPage({ event_type: "prevention_entered" });

    topicsTrack.value?.addEventListener("wheel", redirectWheel, {
        passive: false,
    });
    topicsTrack.value?.addEventListener("scroll", updateTopicIndicator, {
        passive: true,
    });
    window.addEventListener("resize", updateTopicIndicator);

    window.addEventListener("scroll", onUserScroll, { passive: true });
    window.addEventListener("click", markEngaged, { once: true, passive: true });

    window.addEventListener("pagehide", sendExit);
    document.addEventListener("visibilitychange", onVisibilityChange);

    nextTick(() => {
        updateTopicIndicator();
        scrollToTopicFromHash();
    });

    window.addEventListener('hashchange', scrollToTopicFromHash);
});

onUnmounted(() => {
    clearTopicScrollTimer();
    if (topicScrollFrame) cancelAnimationFrame(topicScrollFrame);
    topicsTrack.value?.removeEventListener("wheel", redirectWheel);
    topicsTrack.value?.removeEventListener("scroll", updateTopicIndicator);
    window.removeEventListener("resize", updateTopicIndicator);
    window.removeEventListener("scroll", onUserScroll);
    window.removeEventListener("click", markEngaged);
    window.removeEventListener("pagehide", sendExit);
    document.removeEventListener("visibilitychange", onVisibilityChange);
    window.removeEventListener('hashchange', scrollToTopicFromHash);

    sendExit();
});
</script>

<template>
    <div
        class="prevention relative overflow-hidden bg-beige-50 text-light-palette-black"
        style="--prevention-accent: var(--cobrand-primary-on-light, #ff6600)"
    >
        <BackgroundMotifs />

        <div class="relative z-10">
            <h1 class="sr-only">Prévention — informations sur le don du sang</h1>
            <div class="relative">
                <section
                    class="relative z-10 flex flex-col bg-beige-50/40 pt-16 pb-16 lg:min-h-[820px] lg:pt-[140px] lg:pb-20"
                >
                    <div class="px-4 lg:px-[60px]">
                        <div class="lg:max-w-[56%]">
                            <PreventionHeading title="Mon don, mon impact" />
                            <p
                                class="mt-6 whitespace-pre-line text-regular leading-relaxed lg:text-h4"
                            >
                                {{ impactText }}
                            </p>
                            <img
                                :src="figures.impact"
                                alt="Goutte de sang qui salue"
                                class="mx-auto mt-8 w-[210px] select-none lg:hidden"
                            />
                        </div>

                        <p
                            class="mt-10 max-w-[1100px] text-h5 font-bold leading-snug lg:mt-14 lg:text-h3"
                        >
                            <span
                                class="text-h2 font-bold text-[color:var(--prevention-accent)] lg:text-[40px]"
                                >45</span
                            >
                            minutes de votre temps, votre don peut sauver
                            jusqu'à
                            <span
                                class="text-h2 font-bold text-[color:var(--prevention-accent)] lg:text-[40px]"
                                >3</span
                            >
                            vies sans quitter votre lieu de travail.
                        </p>
                    </div>
                </section>

                <section
                    class="relative z-10 flex flex-col bg-beige-200/30 pt-16 pb-16 lg:min-h-[820px] lg:pt-[140px] lg:pb-20"
                >
                    <div class="px-4 lg:px-[60px]">
                        <div class="lg:max-w-[56%]">
                            <PreventionHeading
                                title="J'ai peur de la piqûre !"
                            />
                            <p
                                class="mt-6 text-regular leading-relaxed lg:text-h4"
                            >
                                {{ peurIntro }}
                            </p>
                            <p
                                class="mt-6 text-regular leading-relaxed lg:text-h4"
                            >
                                {{ peurDetail }}
                            </p>

                            <p class="mt-8 text-regular font-bold lg:text-h5">
                                Quelques gestes simples aident à mieux vivre le
                                moment :
                            </p>
                            <ul class="mt-5 flex flex-col gap-5">
                                <li
                                    v-for="tip in tips"
                                    :key="tip.icon"
                                    class="flex items-center gap-5"
                                >
                                    <span
                                        class="flex h-[61px] w-[61px] shrink-0 items-center justify-center rounded-full border border-black/40 text-[color:var(--prevention-accent)]"
                                        :style="peachCircle"
                                    >
                                        <PreventionGlyph :name="tip.icon" />
                                    </span>
                                    <span
                                        class="text-regular text-texte-primary-dark lg:text-h5"
                                        >{{ tip.text }}</span
                                    >
                                </li>
                            </ul>

                            <p
                                class="mt-8 text-regular leading-relaxed lg:text-h4"
                            >
                                Ils ont l'habitude et savent exactement comment
                                vous accompagner.
                            </p>
                            <img
                                :src="figures.peur"
                                alt="Goutte de sang inquiète face à une seringue"
                                class="mx-auto mt-8 w-[280px] select-none lg:hidden"
                            />
                        </div>
                    </div>
                </section>

                <section
                    class="relative z-10 flex flex-col bg-beige-50/40 pt-16 pb-16 lg:min-h-[820px] lg:pt-[140px] lg:pb-20"
                >
                    <div class="px-4 lg:px-[60px]">
                        <div class="lg:max-w-[56%]">
                            <PreventionHeading
                                title="Que se passe-t-il pendant le don ?"
                            />
                            <p
                                class="mt-6 whitespace-pre-line text-regular leading-relaxed lg:text-h4"
                            >
                                {{ pendantText }}
                            </p>
                            <img
                                :src="figures.pendant"
                                alt="Goutte de sang en blouse de médecin"
                                class="mx-auto mt-8 w-[220px] select-none lg:hidden"
                            />
                        </div>
                    </div>
                </section>

                <section
                    class="relative z-10 flex flex-col bg-beige-200/30 pt-16 pb-16 lg:min-h-[820px] lg:pt-[140px] lg:pb-20"
                >
                    <div class="px-4 lg:px-[60px]">
                        <div class="lg:max-w-[56%]">
                            <PreventionHeading title="Que devient mon sang ?" />
                            <p
                                class="mt-6 whitespace-pre-line text-regular leading-relaxed lg:text-h4"
                            >
                                {{ devientText }}
                            </p>
                            <img
                                :src="figures.devient"
                                alt="Poche de sang"
                                class="mx-auto mt-8 w-[220px] select-none lg:hidden"
                            />
                        </div>
                    </div>
                </section>

                <div
                    class="pointer-events-none absolute inset-0 z-[15] hidden lg:block"
                    aria-hidden="true"
                >
                    <svg
                        viewBox="0 0 100 100"
                        preserveAspectRatio="none"
                        fill="none"
                        class="h-full w-full overflow-visible"
                    >
                        <path
                            :d="cablePath"
                            stroke="#D0C7C7"
                            stroke-width="13"
                            stroke-linecap="round"
                            vector-effect="non-scaling-stroke"
                        />
                        <path
                            :d="cablePath"
                            stroke="#BD1D1D"
                            stroke-width="6"
                            stroke-linecap="round"
                            vector-effect="non-scaling-stroke"
                        />
                    </svg>
                </div>

                <div
                    class="pointer-events-none absolute inset-0 z-20 hidden lg:block"
                    aria-hidden="true"
                >
                    <img
                        v-for="(fig, i) in cableFigures"
                        :key="i"
                        :src="fig.src"
                        :alt="fig.alt"
                        class="absolute select-none"
                        :style="figureStyle(fig)"
                    />
                </div>
            </div>

            <section class="relative z-10 py-16 lg:py-20">
                <div class="px-4 lg:px-[60px]">
                    <PreventionHeading
                        title="Pourquoi je ne suis pas éligible ?"
                        centered
                    />

                    <p
                        class="mt-10 mx-auto text-center text-h5 font-bold leading-snug lg:max-w-[800px] lg:text-h3"
                    >
                        Ne pas pouvoir donner son sang aujourd'hui ne signifie pas que c'est définitif !
                    </p>

                    <div
                        class="mt-10 flex flex-col items-center gap-10 w-full lg:flex-row lg:justify-center lg:gap-12"
                    >
                        <div class="flex max-w-[620px] flex-col gap-8">
                            <ul class="flex flex-col gap-6">
                                <li
                                    v-for="(note, i) in eligibilityNotes"
                                    :key="i"
                                    class="flex items-center gap-5"
                                >
                                    <span
                                        class="flex h-[75px] w-[75px] shrink-0 items-center justify-center rounded-full border border-black/40 text-[color:var(--prevention-accent)]"
                                        :style="peachCircle"
                                    >
                                        <img :src="note.image" alt="" class="h-[40px] w-auto object-contain" />
                                    </span>
                                    <span
                                        class="text-regular leading-relaxed lg:text-h4"
                                        >{{ note.text }}</span
                                    >
                                </li>
                            </ul>
                        </div>
                        <img
                            :src="figures.eligible"
                            alt="Goutte de sang qui s'interroge"
                            class="w-[230px] shrink-0 select-none lg:w-[280px]"
                        />
                    </div>
                </div>

                <div
                    ref="topicsTrack"
                    role="region"
                    tabindex="0"
                    aria-label="Raisons de non-éligibilité — utilisez les flèches gauche et droite pour faire défiler"
                    @keydown="onTopicsKeydown"
                    class="topics-scroll mt-20 flex gap-6 overflow-x-auto px-4 pb-6 lg:mt-28 lg:px-[60px]"
                >
                    <PreventionCard
                        v-for="topic in preventionTopics"
                        :key="topic.slug"
                        :topic="topic"
                    />
                </div>

                <div
                    v-if="topicPageCount > 1"
                    class="mt-8 hidden items-center justify-center gap-4 px-4 lg:mt-10 lg:flex"
                >
                    <button
                        type="button"
                        class="slider-arrow"
                        :disabled="topicPage === 0"
                        aria-label="Cartes précédentes"
                        @click="goToTopicPage(topicPage - 1)"
                    >
                        <span
                            class="slider-arrow-icon"
                            :style="arrowMaskStyle(true)"
                        />
                    </button>

                    <div class="flex items-center gap-2">
                        <button
                            v-for="page in topicPageCount"
                            :key="page"
                            type="button"
                            class="shrink-0"
                            :aria-label="`Aller à la page ${page}`"
                            :aria-current="
                                topicPage === page - 1 ? 'true' : undefined
                            "
                            @click="goToTopicPage(page - 1)"
                        >
                            <img
                                :src="
                                    topicPage === page - 1
                                        ? dropActive
                                        : dropInactive
                                "
                                alt=""
                                class="h-7 w-auto select-none"
                            />
                        </button>
                    </div>

                    <button
                        type="button"
                        class="slider-arrow"
                        :disabled="topicPage === topicPageCount - 1"
                        aria-label="Cartes suivantes"
                        @click="goToTopicPage(topicPage + 1)"
                    >
                        <span
                            class="slider-arrow-icon"
                            :style="arrowMaskStyle(false)"
                        />
                    </button>
                </div>
            </section>

            <section class="relative z-10 py-16 lg:py-20">
                <div
                    class="flex flex-col items-center gap-12 px-4 lg:flex-row lg:items-center lg:gap-24 lg:px-[60px]"
                >
                    <img
                        :src="figures.apres"
                        alt="Goutte de sang qui fait au revoir"
                        class="w-[260px] select-none lg:w-[380px]"
                    />
                    <div class="lg:max-w-[60%]">
                        <PreventionHeading title="Et après le don ?" />
                        <p
                            class="mt-6 whitespace-pre-line text-regular leading-relaxed lg:text-h4"
                        >
                            {{ apresText }}
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<style scoped>
.topics-scroll::-webkit-scrollbar {
    height: 8px;
}
.topics-scroll::-webkit-scrollbar-track {
    background-color: transparent;
}
.topics-scroll::-webkit-scrollbar-thumb {
    border-radius: 9999px;
    background-color: color-mix(in srgb, var(--prevention-accent) 55%, white);
}

@media (min-width: 1024px) {
    .topics-scroll {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .topics-scroll::-webkit-scrollbar {
        display: none;
    }
}

.slider-arrow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.slider-arrow:disabled {
    cursor: default;
    opacity: 0.3;
}
.slider-arrow:focus-visible {
    outline: 2px solid var(--prevention-accent);
    outline-offset: 2px;
}
.slider-arrow-icon {
    display: block;
    height: 34px;
    width: 34px;
    background-color: var(--prevention-accent);
}
</style>
