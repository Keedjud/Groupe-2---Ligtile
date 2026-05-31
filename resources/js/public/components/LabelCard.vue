<script setup>
import { computed } from "vue";

const props = defineProps({
    company: {
        type: Object,
        required: true,
    },
});

const logoUrl = computed(
    () => props.company.collections?.[0]?.logo_url ?? null,
);
const labelStartDate = computed(
    () => props.company.labels?.[0]?.pivot?.start_date ?? null,
);
const badgeImg = "/images/" + "label_empty.png";

function formatDate(dateString) {
    if (!dateString) return "";
    const d = new Date(dateString);
    return d.toLocaleDateString("fr-CH", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
}
</script>

<template>
    <div
        class="flex flex-col items-center gap-4 rounded-[10px] bg-violet-50 p-2.5 shadow-[0_4px_4px_1px_rgba(104,23,100,0.30)] lg:gap-[22px]"
    >
        <!-- Logo + Badge Label -->
        <div class="flex w-full items-center justify-between px-1">
            <!-- Logo entreprise -->
            <div
                class="flex h-[42px] w-[75px] items-center justify-center overflow-hidden lg:h-[50px]"
            >
                <img
                    v-if="logoUrl"
                    :src="logoUrl"
                    :alt="company.name"
                    class="max-h-full max-w-full object-contain"
                />
                <div
                    v-else
                    class="flex h-10 w-16 items-center justify-center rounded bg-violet-100 text-xs text-violet-600"
                >
                    {{ company.name }}
                </div>
            </div>

            <!-- Badge label -->
            <div class="flex shrink-0 items-center justify-center">
                <img
                    :src="badgeImg"
                    alt="Label CTS"
                    class="h-[50px] w-[48px] object-contain lg:h-[60px] lg:w-[57px]"
                />
            </div>
        </div>

        <!-- Infos -->
        <div class="flex flex-col items-start gap-2 rounded-[10px]">
            <!-- Taille entreprise (desktop only) -->
            <div class="hidden items-center gap-5 lg:flex">
                <div class="flex h-6 w-6 items-center justify-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-[18px] w-[18px] text-vert-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3"
                        />
                    </svg>
                </div>
                <span
                    class="py-2.5 text-center font-sans text-regular text-violet-950"
                >
                    {{ company.size_label }}
                </span>
            </div>

            <!-- Date -->
            <div class="flex items-center gap-5">
                <div class="flex h-6 w-6 items-center justify-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-[18px] w-[18px] text-vert-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect
                            x="3"
                            y="4"
                            width="18"
                            height="18"
                            rx="2"
                            ry="2"
                        />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>
                <span
                    class="py-2.5 text-center font-sans text-regular text-violet-950"
                >
                    {{ formatDate(labelStartDate) }}
                </span>
            </div>
        </div>

        <!-- Bouton Voir la collecte (desktop only) -->
        <a
            :href="'#/prendre-rdv'"
            class="hidden h-11 w-[166px] items-center justify-center rounded-[40px] bg-white font-sans text-regular text-violet-900 underline shadow-[0_0_4px_rgba(0,0,0,0.25)] transition-colors hover:bg-violet-50 lg:inline-flex"
        >
            Voir la collecte
        </a>
    </div>
</template>
