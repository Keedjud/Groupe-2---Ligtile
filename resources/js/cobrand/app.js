import { createApp } from 'vue';
import App from './App.vue';
import '../../css/app.css';

const el = document.getElementById('app');
const collecteToken = el?.dataset?.collecteToken ?? null;

createApp(App, { collecteToken }).mount(el);


