import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import '../../css/app.css';

const el = document.getElementById('app');
const collecteId = el?.dataset?.collecteId ?? null;

const app = createApp(App, { collecteId });
app.provide('collecteId', collecteId);
app.use(router);
app.mount(el);
