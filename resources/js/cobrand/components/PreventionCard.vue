<script setup>
defineProps({
    topic: { type: Object, required: true },
});
</script>

<template>
    <article
        :id="'topic-' + topic.slug"
        class="flex w-[calc(100vw-2rem)] shrink-0 flex-col rounded-3xl border border-black/5 bg-beige-100 p-4 shadow-[0_4px_12px_rgba(0,0,0,0.06)] sm:w-[420px] lg:w-[470px] lg:p-5"
    >
        <h3
            class="flex h-[48px] items-start justify-center text-center text-h5 font-semibold leading-tight text-[color:var(--prevention-accent)] sm:h-[64px] sm:text-h4 lg:h-[72px] lg:text-h3"
        >
            {{ topic.title }}
        </h3>

        <div class="flex h-[110px] items-center justify-center py-1 sm:h-[150px] sm:py-2">
            <img
                :src="topic.illustration"
                :alt="topic.title"
                class="max-h-full w-auto max-w-full select-none object-contain"
            />
        </div>

        <div class="mt-2 flex-1 rounded-2xl bg-form-bg p-4 sm:mt-3 sm:p-5">
            <p
                class="whitespace-pre-line text-center text-xs leading-relaxed text-violet-950 sm:text-small lg:text-regular"
            >
                {{ topic.body }}
            </p>
        </div>
    </article>
</template>
