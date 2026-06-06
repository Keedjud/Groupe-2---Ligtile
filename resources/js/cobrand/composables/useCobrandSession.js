import { ref } from "vue";
import { useFetchApi } from "@/composables/api/useFetchApi";

const { fetchApi } = useFetchApi("/api/v1");

const SESSION_KEY = "cobrand_session_id";

// Tracking
const collectionId = ref(null);
const sessionId    = ref(null);

// Branding entreprise
const companyName  = ref(null);
const nbEmployee   = ref(null);
const logoUrl      = ref(null);
const theme        = ref(null);

// Informations de la collecte
const startDate       = ref(null);
const endDate         = ref(null);
const capacity        = ref(null);
const nbInscrits      = ref(null);
const placesRestantes = ref(null);
const tauxRemplissage = ref(null);
const onedocUrl       = ref(null);
const contactEmail    = ref(null);
const contactPhone    = ref(null);
const venue           = ref(null);

// État de chargement
const sessionError = ref(null);

let initPromise = null;

function ensureSessionId() {
    let id = sessionStorage.getItem(SESSION_KEY);
    if (!id) {
        id = crypto.randomUUID();
        sessionStorage.setItem(SESSION_KEY, id);
    }
    sessionId.value = id;
}

export function initSession(token) {
    if (initPromise) return initPromise;

    ensureSessionId();

    initPromise = fetchApi({ url: `/cobrand/${token}` })
        .then((data) => {
            collectionId.value    = data.id;
            companyName.value     = data.company_name;
            nbEmployee.value      = data.nb_employee;
            logoUrl.value         = data.logo_url;
            theme.value           = data.theme;
            startDate.value       = data.start_date;
            endDate.value         = data.end_date;
            capacity.value        = data.capacity;
            nbInscrits.value      = data.nb_inscrits;
            placesRestantes.value = data.places_restantes;
            tauxRemplissage.value = data.taux_remplissage;
            onedocUrl.value       = data.onedoc_url;
            contactEmail.value    = data.contact_email;
            contactPhone.value    = data.contact_phone;
            venue.value           = data.venue;
        })
        .catch((err) => {
            sessionError.value = err;
            initPromise = null; // permet de réessayer
        });

    return initPromise;
}

async function trackQuiz({ event_type, part = null, question_slug = null, answer_result = null }) {
    await initPromise;
    if (!collectionId.value || !sessionId.value) return;

    try {
        await fetchApi({
            url: "/quiz/event",
            method: "POST",
            data: {
                collection_id: collectionId.value,
                session_id:    sessionId.value,
                event_type,
                part,
                question_slug,
                answer_result,
            },
        });
    } catch {
        // Silencieux : le tracking ne doit jamais bloquer l'UX
    }
}

async function trackPage({ event_type, engaged = null, time_on_page = null }) {
    await initPromise;
    if (!collectionId.value || !sessionId.value) return;

    try {
        await fetchApi({
            url: "/page/event",
            method: "POST",
            data: {
                collection_id: collectionId.value,
                session_id:    sessionId.value,
                event_type,
                engaged,
                time_on_page,
            },
        });
    } catch {
        // Silencieux
    }
}

export function useCobrandSession() {
    return {
        // Tracking interne (pas besoin d'y toucher dans les vues)
        collectionId,
        sessionId,

        // Branding — utiliser pour le header et le thème CSS
        companyName,
        nbEmployee,
        logoUrl,
        theme,

        // Données de la collecte — utiliser dans les vues
        startDate,
        endDate,
        capacity,
        nbInscrits,
        placesRestantes,
        tauxRemplissage,
        onedocUrl,
        contactEmail,
        contactPhone,
        venue,

        // État de chargement
        sessionError,

        // Actions
        initSession,
        trackQuiz,
        trackPage,
    };
}
