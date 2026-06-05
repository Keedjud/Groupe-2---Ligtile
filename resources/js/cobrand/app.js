import { createApp } from 'vue';
import App from './App.vue';
import '../../css/app.css';

const el = document.getElementById('app');
const cobrandToken = el.dataset.cobrandToken ?? null;

createApp(App, { cobrandToken }).mount(el);
