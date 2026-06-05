import { ref } from "vue";
import { useFetchApi } from "@/composables/api/useFetchApi";

const { fetchApi } = useFetchApi("/api/v1");

const SESSION_KEY = "cobrand_session_id";

const collectionId = ref(null);
const sessionId = ref(null);
const onedocUrl = ref(null);

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
            collectionId.value = data.id;
            onedocUrl.value = data.onedoc_url;
        })
        .catch(() => {});

    return initPromise;
}

async function trackQuiz({
    event_type,
    part = null,
    question_slug = null,
    answer_result = null,
}) {
    await initPromise;
    if (!collectionId.value || !sessionId.value) return;

    try {
        await fetchApi({
            url: "/quiz/event",
            method: "POST",
            data: {
                collection_id: collectionId.value,
                session_id: sessionId.value,
                event_type,
                part,
                question_slug,
                answer_result,
            },
        });
    } catch {
        return;
    }
}

export function useCobrandSession() {
    return { collectionId, sessionId, onedocUrl, initSession, trackQuiz };
}
