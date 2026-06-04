<script setup>
import { useTrophees } from '../composables/useTrophees'
import { useDisclosure } from '@/composables/useDisclosure'
import { companyForRank } from '../composables/usePodiumLogos'
import PodiumDisplay from '../components/PodiumDisplay.vue'

const { podium, history, loading, error, fetchNow } = useTrophees()
const { isOpen: showCriteria, toggle: toggleCriteria } = useDisclosure()
</script>

<template>
  <div>
    <!-- ===== Section A : Hero ===== -->
    <section class="bg-violet-100 px-4 py-12 lg:px-[60px] lg:py-0 lg:min-h-[calc(100dvh-110px)] lg:flex lg:items-center">
      <div class="mx-auto flex max-w-[1512px] w-full flex-col items-center gap-8 lg:flex-row lg:gap-16">
        <!-- Image de trophy_top visible uniquement sur mobile au sommet -->
        <img
          :src="'/images/classement/trophy-top.png'"
          alt=""
          class="md:hidden w-[176px] h-auto object-contain"
        />
        <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
        <!-- Colonne gauche : Texte -->
        <div class="flex flex-1 flex-col gap-10 lg:max-w-[800px]">
          <h1 class="font-sans text-h1 font-semibold text-black">
            Rejoignez les entreprises qui font la différence.
          </h1>
          <p class="font-sans text-h5 font-normal text-black">
            Chaque année, les HUG récompensent les trois premières entreprises genevoises les plus engagées dans la promotion du don du sang auprès de leurs collaborateurs.
          </p>
          <p class="font-sans text-h3 font-bold text-vert-400">
            Et si la vôtre faisait partie du podium ?
          </p>
          <p class="font-sans text-h5 font-normal text-black">
            Depuis 2008, le Trophée de la Générosité valorise l'implication des entreprises partenaires et l'efficacité de leurs collectes. Parce qu'un engagement mérite d'être reconnu, les HUG distinguent chaque année les entreprises qui font vraiment la différence.
          </p>
          <p class="font-sans text-h3 font-bold text-vert-400">
            Un trophée à exposer fièrement
          </p>
          <p class="font-sans text-h5 font-normal text-texte-primary-dark">
            Au-delà de la reconnaissance digitale, chaque entreprise lauréate reçoit une plaque officielle signée par les HUG. Un objet réel, conçu pour être exposé dans vos locaux et témoigner de l'engagement de vos équipes.
          </p>
        </div>

        <!-- Colonne droite : Image + bouton -->
        <div class="flex flex-col items-center justify-center gap-10 lg:w-[480px] w-full lg:flex-shrink-0">
          <img
            :src="'/images/classement/trophy-top.png'"
            alt=""
            class="hidden md:block w-[178px] h-auto object-contain"
          />
          <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
          <div class="flex h-[45px] w-[250px] cursor-pointer items-center justify-center gap-2 rounded-[40px] bg-texte-primary-light px-3 py-2 shadow-[0_0_4px_rgba(0,0,0,0.25)] transition-shadow hover:shadow-md">
            <span class="font-sans text-regular text-texte-secondary">Découvrir le trophée →</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== Section B : Podium ===== -->
    <section class="flex flex-col items-center gap-8 px-4 py-12 lg:pt-[140px] lg:pb-[60px]">
      <!-- Titres -->
      <div class="mb-8 flex flex-col items-center lg:mb-24">
        <h2 class="text-center font-sans text-h1 font-semibold text-violet-900">
          Le Trophée de la Générosité
        </h2>
        <p class="text-center font-sans text-h4 font-normal text-violet-900">
          Podium {{ podium?.year ?? '---' }}
        </p>
      </div>

      <!-- État loading -->
      <div v-if="loading" class="flex flex-col items-center gap-4 py-12">
        <div class="h-12 w-12 animate-spin rounded-full border-4 border-violet-200 border-t-violet-900"></div>
        <p class="font-sans text-regular text-violet-900">Chargement du podium...</p>
      </div>

      <!-- État erreur -->
      <div v-else-if="error" class="flex flex-col items-center gap-4 rounded-lg bg-rouge-100 px-6 py-8 text-center">
        <p class="font-sans text-h5 text-rouge-600">Une erreur est survenue</p>
        <button
          @click="fetchNow"
          class="rounded-lg bg-button-primary px-6 py-2 font-sans text-regular text-white transition-colors hover:bg-violet-800"
        >
          Réessayer
        </button>
      </div>

      <!-- Podium vide -->
      <div v-else-if="!podium || !podium.companies?.length" class="py-12 text-center">
        <p class="font-sans text-h4 text-texte-primary-dark">Aucun podium cette année</p>
      </div>

      <!-- Podium -->
      <div v-else class="flex w-full max-w-[1000px] flex-col items-center">
        <PodiumDisplay :companies="podium.companies" />

        <!-- Critères d'attribution — aligné avec le bord gauche du SVG -->
        <button
          @click="toggleCriteria"
          class="mt-2 self-start font-sans text-regular text-violet-900 underline transition-colors hover:text-violet-600"
        >
          {{ showCriteria ? 'Masquer les critères d\'attribution' : 'Afficher les critères d\'attribution' }}
        </button>
      </div>
    </section>

    <!-- Overlay des critères d'évaluation -->
    <Transition
      enter-active-class="transition-opacity duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="showCriteria"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
        @click.self="toggleCriteria"
      >
        <div class="relative w-full max-w-[857px] md:h-[595px] rounded-[25px] bg-form-bg p-6 md:p-[47px] flex flex-col justify-between overflow-y-auto md:overflow-hidden">
          <!-- Bouton fermer -->
          <button
            @click="toggleCriteria"
            class="absolute right-4 top-4 md:right-[40px] md:top-[40px] flex h-8 w-8 items-center justify-center rounded-full text-texte-primary-dark transition-colors hover:bg-violet-100 z-10"
          >
            ✕
          </button>

          <!-- Top part: Title + Personnage absolute -->
          <div class="relative w-full">
            <h3 class="max-w-[516px] font-sans text-[24px] md:text-[32px] font-semibold leading-tight text-texte-primary-dark md:mt-[32px]">
              Quels sont les critères pour remporter un trophée ?
            </h3>
            <img
              :src="'/images/illustrations/infos.png'"
              alt="Mascotte goutte de sang qui se pose des questions"
              class="hidden md:block absolute right-[126px] top-[24px] w-[117px] h-[174px] object-contain"
            />
          </div>

          <!-- Bottom part: Criteria List -->
          <div class="mt-8 md:mt-0 flex flex-col gap-6 md:gap-7">
            <!-- Régularité -->
            <div class="flex items-center gap-6">
              <div class="flex h-[50px] w-[50px] md:h-[60px] md:w-[60px] flex-shrink-0 items-center justify-center rounded-full bg-[#E5F5F0]">
                <img :src="'/images/icons/regularite.png'" alt="" class="h-[28px] w-[28px] md:h-[36px] md:w-[36px] object-contain" />
              </div>
              <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
              <div>
                <p class="font-sans text-[16px] md:text-[22px] font-bold text-texte-primary-dark leading-snug">Régularité</p>
                <p class="font-sans text-[14px] md:text-[18px] font-normal text-texte-primary-dark/80 leading-snug">Engagement durable dans la collecte de sang</p>
              </div>
            </div>

            <!-- Nombre d'inscrits -->
            <div class="flex items-center gap-6">
              <div class="flex h-[50px] w-[50px] md:h-[60px] md:w-[60px] flex-shrink-0 items-center justify-center rounded-full bg-[#E5F5F0]">
                <img :src="'/images/icons/inscrit.png'" alt="" class="h-[28px] w-[28px] md:h-[36px] md:w-[36px] object-contain" />
              </div>
              <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
              <div>
                <p class="font-sans text-[16px] md:text-[22px] font-bold text-texte-primary-dark leading-snug">Nombre d'inscrits</p>
                <p class="font-sans text-[14px] md:text-[18px] font-normal text-texte-primary-dark/80 leading-snug">Participation des collaborateurs aux collectes organisées</p>
              </div>
            </div>

            <!-- Taux d'engagement -->
            <div class="flex items-center gap-6">
              <div class="flex h-[50px] w-[50px] md:h-[60px] md:w-[60px] flex-shrink-0 items-center justify-center rounded-full bg-[#E5F5F0]">
                <img :src="'/images/icons/engagement.png'" alt="" class="h-[28px] w-[28px] md:h-[36px] md:w-[36px] object-contain" />
              </div>
              <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
              <div>
                <p class="font-sans text-[16px] md:text-[22px] font-bold text-texte-primary-dark leading-snug">Taux d'engagement</p>
                <p class="font-sans text-[14px] md:text-[18px] font-normal text-texte-primary-dark/80 leading-snug">Participation effective lors des collectes</p>
              </div>
            </div>

            <!-- Appréciation du jury -->
            <div class="flex items-center gap-6">
              <div class="flex h-[50px] w-[50px] md:h-[60px] md:w-[60px] flex-shrink-0 items-center justify-center rounded-full bg-[#E5F5F0]">
                <img :src="'/images/icons/appreciation.png'" alt="" class="h-[28px] w-[28px] md:h-[36px] md:w-[36px] object-contain" />
              </div>
              <!--Image purement décorative, pas besoin d'alt, bonne pratique-->
              <div>
                <p class="font-sans text-[16px] md:text-[22px] font-bold text-texte-primary-dark leading-snug">Appréciation du jury</p>
                <p class="font-sans text-[14px] md:text-[18px] font-normal text-texte-primary-dark/80 leading-snug">Qualité des actions de communications</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ===== Section D : Tableau des lauréats ===== -->
    <section class="bg-violet-100 px-4 py-10 lg:px-[60px] lg:py-[40px]">
      <div class="mx-auto max-w-[1512px]">
        <h2 class="mb-10 font-sans text-h1 font-semibold text-button-primary">
          Tous les lauréats
        </h2>

        <!-- État loading -->
        <div v-if="loading" class="flex flex-col items-center gap-4 py-12">
          <div class="h-10 w-10 animate-spin rounded-full border-4 border-violet-200 border-t-violet-900"></div>
          <p class="font-sans text-regular text-violet-900">Chargement des lauréats...</p>
        </div>

        <!-- État erreur -->
        <div v-else-if="error" class="flex flex-col items-center gap-4 rounded-lg bg-rouge-100 px-6 py-8 text-center">
          <p class="font-sans text-h5 text-rouge-600">Une erreur est survenue</p>
          <button
            @click="fetchNow"
            class="rounded-lg bg-button-primary px-6 py-2 font-sans text-regular text-white transition-colors hover:bg-violet-800"
          >
            Réessayer
          </button>
        </div>

        <!-- Tableau vide -->
        <div v-else-if="!history.length" class="py-12 text-center">
          <p class="font-sans text-h4 text-texte-primary-dark">Aucun lauréat pour le moment</p>
        </div>

        <!-- Tableau -->
        <!-- Desktop Version (Tableau) -->
        <div
          class="hidden md:block rounded-[20px] border border-violet-900 bg-form-bg p-6 lg:p-6"
        >
          <div class="overflow-x-auto">
            <table class="w-full min-w-[700px]">
              <thead>
                <tr class="border-b border-violet-900/30">
                  <th class="pb-4 text-center font-sans text-h3 font-bold text-violet-900">
                    Année
                  </th>
                  <th class="pb-4 text-center font-sans text-h2 font-normal text-violet-900">
                    Première place
                  </th>
                  <th class="pb-4 text-center font-sans text-h2 font-normal text-violet-900">
                    Deuxième place
                  </th>
                  <th class="pb-4 text-center font-sans text-h2 font-normal text-violet-900">
                    Troisième place
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in history"
                  :key="item.year"
                  :class="index < history.length - 1 ? 'border-b border-violet-900/30' : ''"
                >
                  <td class="py-6 text-center font-sans text-h5 font-normal text-black">
                    {{ item.year }}
                  </td>
                  <td class="py-6 text-center font-sans text-h5 font-normal text-black">
                    <template v-if="companyForRank(item.companies, 1)">
                      {{ companyForRank(item.companies, 1).name }}<br />
                      <span class="text-small text-light-secondary">{{ companyForRank(item.companies, 1).participant_count }} participants</span>
                    </template>
                    <span v-else class="text-light-tertiary">---</span>
                  </td>
                  <td class="py-6 text-center font-sans text-h5 font-normal text-black">
                    <template v-if="companyForRank(item.companies, 2)">
                      {{ companyForRank(item.companies, 2).name }}<br />
                      <span class="text-small text-light-secondary">{{ companyForRank(item.companies, 2).participant_count }} participants</span>
                    </template>
                    <span v-else class="text-light-tertiary">---</span>
                  </td>
                  <td class="py-6 text-center font-sans text-h5 font-normal text-black">
                    <template v-if="companyForRank(item.companies, 3)">
                      {{ companyForRank(item.companies, 3).name }}<br />
                      <span class="text-small text-light-secondary">{{ companyForRank(item.companies, 3).participant_count }} participants</span>
                    </template>
                    <span v-else class="text-light-tertiary">---</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Mobile Version (Cards) -->
        <div class="md:hidden flex flex-row gap-6 overflow-x-auto pb-4 w-full snap-x snap-mandatory px-[51px] scroll-smooth">
          <div
            v-for="item in history"
            :key="item.year"
            class="w-[290px] flex-shrink-0 p-3 bg-[#EFD2F4] rounded-[26px] flex flex-col items-center gap-2 snap-center"
          >
            <!-- Year Header -->
            <div class="w-full py-1 px-1 flex justify-start items-center">
              <span class="font-sans text-[20px] font-medium text-black">{{ item.year }}</span>
            </div>

            <!-- Ranks Container -->
            <div class="w-full rounded-[20px] overflow-hidden flex flex-col bg-form-bg">
              <!-- Rank 1 -->
              <div class="w-full py-3 px-4 border-b border-[#D781E0] flex items-center gap-4">
                <span class="w-4 text-center font-sans text-[20px] font-normal text-black">1</span>
                <div class="h-8 w-[1px] bg-violet-900/30"></div>
                <div class="flex-1 text-center font-sans text-[16px] text-black">
                  <template v-if="companyForRank(item.companies, 1)">
                    <span class="font-semibold text-[16px]">{{ companyForRank(item.companies, 1).name }}</span>
                    <br />
                    <span class="text-[13px] text-texte-primary-dark/70">{{ companyForRank(item.companies, 1).participant_count }} participants</span>
                  </template>
                  <span v-else class="text-texte-primary-dark/40">---</span>
                </div>
              </div>

              <!-- Rank 2 -->
              <div class="w-full py-3 px-4 border-b border-[#D781E0] flex items-center gap-4">
                <span class="w-4 text-center font-sans text-[20px] font-normal text-black">2</span>
                <div class="h-8 w-[1px] bg-violet-900/30"></div>
                <div class="flex-1 text-center font-sans text-[16px] text-black">
                  <template v-if="companyForRank(item.companies, 2)">
                    <span class="font-semibold text-[16px]">{{ companyForRank(item.companies, 2).name }}</span>
                    <br />
                    <span class="text-[13px] text-texte-primary-dark/70">{{ companyForRank(item.companies, 2).participant_count }} participants</span>
                  </template>
                  <span v-else class="text-texte-primary-dark/40">---</span>
                </div>
              </div>

              <!-- Rank 3 -->
              <div class="w-full py-3 px-4 flex items-center gap-4">
                <span class="w-4 text-center font-sans text-[20px] font-normal text-black">3</span>
                <div class="h-8 w-[1px] bg-violet-900/30"></div>
                <div class="flex-1 text-center font-sans text-[16px] text-black">
                  <template v-if="companyForRank(item.companies, 3)">
                    <span class="font-semibold text-[16px]">{{ companyForRank(item.companies, 3).name }}</span>
                    <br />
                    <span class="text-[13px] text-texte-primary-dark/70">{{ companyForRank(item.companies, 3).participant_count }} participants</span>
                  </template>
                  <span v-else class="text-texte-primary-dark/40">---</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

