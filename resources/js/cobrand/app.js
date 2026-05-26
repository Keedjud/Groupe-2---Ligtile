import { createApp } from 'vue';
import App from './App.vue';
import '../../css/app.css';

const el = document.getElementById('app');
const collecteId = el.dataset.collecteId ?? null;

createApp(App, { collecteId }).mount(el);
