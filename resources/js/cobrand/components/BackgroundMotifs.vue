<script setup>
const ORANGE = "#FF9500";

const ICONS = [
    "/images/cobrand/quizz/heart.svg",
    "/images/cobrand/quizz/croix.svg",
    "/images/cobrand/quizz/seringue.svg",
    "/images/cobrand/quizz/goutte.svg",
];
const SIZES = [70, 90, 110, 130];

function buildMotifs() {
    let seed = 7;
    const rnd = () => {
        seed = (seed * 1103515245 + 12345) & 0x7fffffff;
        return seed / 0x7fffffff;
    };

    const cols = 5;
    const rows = 11;
    const list = [];
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
            list.push({
                src: ICONS[(r * cols + c) % ICONS.length],
                top: ((r + 0.15 + rnd() * 0.7) * (100 / rows)).toFixed(1) + "%",
                left: ((c + 0.1 + rnd() * 0.8) * (100 / cols)).toFixed(1) + "%",
                size: SIZES[Math.floor(rnd() * SIZES.length)],
                rot: Math.round((rnd() * 2 - 1) * 22),
            });
        }
    }
    return list;
}

const motifs = buildMotifs();
</script>

<template>
    <div
        class="pointer-events-none absolute inset-0 z-0 overflow-hidden"
        aria-hidden="true"
    >
        <div
            v-for="(m, i) in motifs"
            :key="i"
            class="absolute"
            :style="{
                top: m.top,
                left: m.left,
                width: m.size + 'px',
                height: m.size + 'px',
                transform: `rotate(${m.rot}deg)`,
                opacity: 0.12,
                backgroundColor: ORANGE,
                maskImage: `url(${m.src})`,
                WebkitMaskImage: `url(${m.src})`,
                maskRepeat: 'no-repeat',
                WebkitMaskRepeat: 'no-repeat',
                maskSize: 'contain',
                WebkitMaskSize: 'contain',
                maskPosition: 'center',
                WebkitMaskPosition: 'center',
            }"
        ></div>
    </div>
</template>
