import { createApp } from 'vue';
import App from './App.vue';
import '../../css/app.css';
import { setDefaultBaseUrl, setDefaultHeaders } from '@/composables/api/useFetchApi';

setDefaultBaseUrl('/api/v1');

function getXsrfToken() {
  const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
  return match ? decodeURIComponent(match[1]) : null;
}

const xsrf = getXsrfToken();
if (xsrf) setDefaultHeaders({ 'X-XSRF-TOKEN': xsrf });

createApp(App).mount('#app');
