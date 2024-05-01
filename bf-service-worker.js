const CACHE_NAME = 'v3';
const urlsToCacheOnPageLoad = [
    '/by_father.php'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
        .then(cache => {
            console.log('Opened cache');
            return cache.addAll(urlsToCacheOnPageLoad);
        })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName != CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', event => {
    // Check if the request is for /by_father.php and strip query parameters if so
    let cacheRequest = event.request;
    if (event.request.url.includes('/by_father.php')) {
        let url = new URL(event.request.url);
        // Create a new request without the search parameters
        cacheRequest = new Request(url.origin + url.pathname, {
            method: event.request.method,
            headers: event.request.headers,
            mode: 'same-origin', // ensure requests are made to the same origin
            credentials: event.request.credentials,
            redirect: 'manual'   // ensure fetches are only for navigation within the same origin
        });
    }

    event.respondWith(
        caches.match(cacheRequest)
        .then(response => {
            if (response) {
                return response; // return the cached response if available
            }
            return fetch(event.request).then(response => {
                // Check if we received a valid response
                if (!response || response.status !== 200 || response.type !== 'basic') {
                    return response;
                }
                let responseToCache = response.clone();

                caches.open(CACHE_NAME)
                .then(cache => {
                    console.log("*****cached another...", cacheRequest.url)
                    cache.put(cacheRequest, responseToCache); // store the response in cache
                });

                return response;
            });
        })
    );
});
