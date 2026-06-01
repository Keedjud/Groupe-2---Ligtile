import { computed, onBeforeUnmount, onMounted, shallowRef } from 'vue';

export function useHashRoute(routes) {
  const defaultRoute = routes[0];
  const currentRoute = shallowRef(defaultRoute);

  function syncRouteFromUrl() {
    const hash = window.location.hash;
    const matched = routes.find(route => route.hash === hash);
    if (matched) {
      currentRoute.value = matched;
      // Les routes ayant une cible de scroll (ex. #/prendre-rdv) gèrent
      // elles-mêmes leur défilement dans la vue concernée ; on ne remet
      // donc pas la page en haut.
      if (!matched.scrollTo) window.scrollTo(0, 0);
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
