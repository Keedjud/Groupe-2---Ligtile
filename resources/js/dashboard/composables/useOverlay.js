import { ref } from 'vue'

const message = ref(null)
const visible = ref(false)
let timer = null

export function useOverlay() {
    function show(msg, duration = 3000) {
        if (timer) clearTimeout(timer)
        message.value = msg
        visible.value = true
        timer = setTimeout(() => { visible.value = false }, duration)
    }

    function hide() {
        if (timer) clearTimeout(timer)
        visible.value = false
    }

    return { message, visible, show, hide }
}
