import { computed, onBeforeUnmount, onMounted, shallowRef } from 'vue';

function normalizeHash(hash) {
  const rawHash = hash.startsWith('#') ? hash.slice(1) : hash;
  const routePart = rawHash.split('#', 2)[0];
  return routePart ? `#${routePart}` : '#';
}

function hasHashFragment(hash) {
  return hash.startsWith('#') && hash.split('#').length > 2;
}

export function useHashRoute(routes) {
  const defaultRoute = routes[0];
  const currentRoute = shallowRef(defaultRoute);

  function syncRouteFromUrl() {
    const routeHash = normalizeHash(window.location.hash);
    const matched = routes.find(route => route.hash === routeHash);
    if (matched) {
      currentRoute.value = matched;
      if (!matched.scrollTo && !hasHashFragment(window.location.hash)) {
        window.scrollTo(0, 0);
      }
    } else {
      currentRoute.value = defaultRoute;
    }
  }

  function navigateTo(hash) {
    window.history.pushState(null, '', hash);
    syncRouteFromUrl();
  }

  onMounted(() => {
    if (window.location.hash === '') {
      window.history.replaceState(null, '', defaultRoute.hash);
    }
    syncRouteFromUrl();
    window.addEventListener('popstate', syncRouteFromUrl);
    window.addEventListener('hashchange', syncRouteFromUrl);
  });

  onBeforeUnmount(() => {
    window.removeEventListener('popstate', syncRouteFromUrl);
    window.removeEventListener('hashchange', syncRouteFromUrl);
  });

  const currentComponent = computed(() => currentRoute.value.component);
  syncRouteFromUrl();

  return { currentComponent, currentRoute, navigateTo };
}
