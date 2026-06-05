import { createApp } from 'vue';
import App from './App.vue';
import '../../css/app.css';

const el = document.getElementById('app');
<<<<<<< HEAD
const collecteId = el?.dataset?.collecteId ?? null;

const app = createApp(App, { collecteId });
app.provide('collecteId', collecteId);
app.mount(el);
=======
const collecteToken = el?.dataset?.collecteToken ?? null;

createApp(App, { collecteToken }).mount(el);


>>>>>>> feature/dashboard
