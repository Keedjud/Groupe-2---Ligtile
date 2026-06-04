import { createApp } from 'vue';
import App from './App.vue';
import '../../css/app.css';

const el = document.getElementById('app');
const collecteId = el?.dataset?.collecteId ?? null;

const app = createApp(App, { collecteId });
app.provide('collecteId', collecteId);
app.mount(el);
