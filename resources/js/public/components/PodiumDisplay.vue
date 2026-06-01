<script setup>
import { computed, toRef } from 'vue'
import { usePodiumLogos } from '../composables/usePodiumLogos'

const props = defineProps({
  companies: { type: Array, default: () => [] },
})

const { companyForRank, logoForRank, onLogoError } = usePodiumLogos(toRef(props, 'companies'))

const ranks = [1, 2, 3]
const logos = computed(() => ranks.map(r => logoForRank(r)))
const rankedCompanies = computed(() => ranks.map(r => companyForRank(r)))
</script>

<template>
  <div class="relative w-full pt-[45%] md:pt-[20%]">

    <!-- Trophée 2ème place (argent) -->
    <img
      :src="'/images/2.png'"
      alt="Deuxième place"
      class="absolute z-[2] h-auto -translate-x-1/2 w-[27%] md:w-[15%] left-[15.6%] md:left-[19%] bottom-[39%] md:bottom-[28%]"
    />

    <!-- Trophée 1ère place (or) -->
    <img
      :src="'/images/1.png'"
      alt="Première place"
      class="absolute z-[2] h-auto -translate-x-1/2 left-1/2 bottom-[49.5%] md:bottom-[35%] w-[27.5%] md:w-[17%]"
    />

    <!-- Trophée 3ème place (bronze) -->
    <img
      :src="'/images/3.png'"
      alt="Troisième place"
      class="absolute z-[2] h-auto -translate-x-1/2 w-[27%] md:w-[15%] left-[84.4%] md:left-[81%] bottom-[30%] md:bottom-[24%]"
    />

    <!-- Leaderboard SVG (desktop / mobile) -->
    <img
      :src="'/images/leaderboard.svg'"
      alt="Estrade des vainqueurs"
      class="hidden md:block w-full h-auto relative z-[1] -scale-x-100"
    />
    <img
      :src="'/images/leaderboard_mobile.svg'"
      alt="Estrade des vainqueurs"
      class="md:hidden w-full h-auto relative z-[1] -scale-x-100"
    />

    <!-- Logo 2ème place -->
    <div class="absolute z-[2] flex items-center justify-center -translate-x-1/2 bottom-[12%] left-[15.6%] md:left-[19%]">
      <img
        v-if="logos[1]"
        :src="logos[1]"
        :alt="rankedCompanies[1]?.name"
        class="max-h-[45px] md:max-h-[103px] max-w-[90px] md:max-w-[200px] object-contain"
        @error="onLogoError($event, 2)"
      />
    </div>

    <!-- Logo 1ère place -->
    <div class="absolute z-[2] flex items-center justify-center -translate-x-1/2 bottom-[12%] left-1/2">
      <img
        v-if="logos[0]"
        :src="logos[0]"
        :alt="rankedCompanies[0]?.name"
        class="max-h-[45px] md:max-h-[103px] max-w-[90px] md:max-w-[200px] object-contain"
        @error="onLogoError($event, 1)"
      />
    </div>

    <!-- Logo 3ème place -->
    <div class="absolute z-[2] flex items-center justify-center -translate-x-1/2 bottom-[12%] left-[84.4%] md:left-[81%] md:bottom-[10%]">
      <img
        v-if="logos[2]"
        :src="logos[2]"
        :alt="rankedCompanies[2]?.name"
        class="max-h-[45px] md:max-h-[103px] max-w-[90px] md:max-w-[200px] object-contain"
        @error="onLogoError($event, 3)"
      />
    </div>

  </div>
</template>
