import { ref, shallowRef } from 'vue'

const defaultHeaders = {
  'Content-Type': 'application/json',
  'X-Requested-With': 'XmlHttpRequest',
  'Accept': 'application/json',
}

let defaultBaseUrl = ''

export function setDefaultHeaders(headers) {
  Object.assign(defaultHeaders, headers)
}

export function setDefaultBaseUrl(url) {
  if (url[url.length - 1] === '/') url = url.slice(0, -1)
  defaultBaseUrl = url
}

export function useFetchApi(baseUrl = null, additionalHeaders = {}) {
  if (baseUrl === null) baseUrl = defaultBaseUrl
  if (baseUrl[baseUrl.length - 1] === '/') baseUrl = baseUrl.slice(0, -1)

  const baseHeaders = { ...defaultHeaders, ...additionalHeaders }

  function fetchApi({
    url,
    data = null,
    method = null,
    headers = {},
    timeout = 5000,
    baseUrl: requestBaseUrl = null,
  }) {
    if (url == null || typeof url !== 'string') throw new Error('The URL must be a string.')

    let effectiveBaseUrl = requestBaseUrl !== null ? requestBaseUrl : baseUrl
    if (effectiveBaseUrl.length && effectiveBaseUrl[effectiveBaseUrl.length - 1] === '/') {
      effectiveBaseUrl = effectiveBaseUrl.slice(0, -1)
    }

    url = url[0] === '/' ? url : '/' + url
    const fullUrl = effectiveBaseUrl + url
    const allHeaders = { ...baseHeaders, ...headers }
    method = method != null ? method.toUpperCase() : data != null ? 'POST' : 'GET'

    return new Promise((resolve, reject) => {
      const controller = new AbortController()
      const timer = setTimeout(() => controller.abort(), timeout)

      fetch(fullUrl, {
        method,
        headers: allHeaders,
        body: data != null ? JSON.stringify(data) : null,
        signal: controller.signal,
        credentials: 'include',
      })
        .then(response => {
          clearTimeout(timer)

          const responseClone = response.clone()

          return response.json()
            .then(json => {
              if (!response.ok) {
                reject({ status: response.status, statusText: response.statusText, data: json })
              } else {
                resolve(json)
              }
            })
            .catch(() => {
              // Corps vide / non-JSON
              return responseClone.text()
                .then(text => {
                  if (response.ok) {
                    resolve(text || null)
                  } else {
                    reject({
                      status: response.status,
                      statusText: response.statusText || 'Error parsing response body as JSON',
                      data: text || null,
                    })
                  }
                })
                .catch(() => {
                  if (response.ok) {
                    resolve(null)
                  } else {
                    reject({
                      status: response.status,
                      statusText: 'Error parsing response body',
                      data: null,
                    })
                  }
                })
            })
        })
        .catch(err => {
          clearTimeout(timer)
          if (err.name === 'AbortError') {
            reject({ status: 0, statusText: 'Timeout', data: null })
          } else {
            reject({ status: 0, statusText: err.message || 'Network error', data: null })
          }
        })
    })
  }

  function fetchApiToRef({ immediate = true, ...options }) {
    const data = ref(null)
    const error = shallowRef(null)
    const loading = ref(immediate)

    if (options?.url == null || typeof options?.url !== 'string') {
      error.value = { status: 0, statusText: 'The URL must be a string.', data: null }
      loading.value = false
      return { data, error, loading, fetchNow: () => {} }
    }

    function fetchNow() {
      loading.value = true
      error.value = null
      fetchApi(options)
        .then(res => {
          data.value = res
          loading.value = false
        })
        .catch(err => {
          error.value = err
          loading.value = false
        })
    }

    if (immediate) fetchNow()

    return { data, error, loading, fetchNow }
  }

  return { fetchApi, fetchApiToRef }
}
