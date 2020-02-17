var version = 'v2.1';
var staticCache = 'bagisto-pwa-static-' + version;
var serverCache = 'bagisto-pwa-server-' + version;
var outSideCache = 'bagisto-pwa-outside-' + version;
var allCaches = [
    staticCache,
    serverCache,
    outSideCache
];

//add
var cacheFiles = [
];

//add scope for /blog and /article so that it can skip /customer scope
self.addEventListener('install', function(event) {
    console.log('[ServiceWorker] Install');

    event.waitUntil(
        caches.open(staticCache).then(function(cache) {
            return cache.addAll(cacheFiles);
        }).then(function() {
            self.skipWaiting();
        })
    );
});

self.addEventListener('activate', function(e) {
    console.log('[ServiceWorker] Activate');

    e.waitUntil(
        // Get all the cache keys (keyList)
        caches.keys().then(function(keyList) {
            return Promise.all(keyList.map(function(key) {
                // If a cached item is saved under a previous cacheName
                if (key !== staticCache && key !== serverCache && key !== outSideCache) {
                    // Delete that cached file
                    console.log('[ServiceWorker] Removing old cache', key);

                    return caches.delete(key);
                }
            }));
        })
    ); // end e.waitUntil

    return self.clients.claim();
});


self.addEventListener('fetch', function(event) {
    if (event.request.method != 'GET')
        return;

    var requestUrl = new URL(event.request.url);


    if (requestUrl.origin === location.origin) {
        if (skipIfThese(requestUrl))
            return;

        event.respondWith(getCacheData(event.request, staticCache));
    } else {
        event.respondWith(getCacheData(event.request, outSideCache));
    }
});

function skipIfThese(requestUrl) {
    if (requestUrl.pathname.indexOf("/pwa") < 0
        && requestUrl.pathname.indexOf("/mobile") < 0
        && requestUrl.pathname.indexOf("/storage") < 0
        && requestUrl.pathname.indexOf("/cache") < 0
        && requestUrl.pathname.indexOf("/api/") < 0
        && requestUrl.pathname.indexOf("/meduim-product-placeholder.png") < 0
        && requestUrl.pathname.indexOf("/small-product-placeholder.png") < 0
        && requestUrl.pathname.indexOf("/large-product-placeholder.png") < 0)
        return true;

    if (['/service-worker.js'].indexOf(requestUrl.pathname.pathname) > -1)
        return true;

    return false;
}

function isItServerRequest(path) {
    if (path == '' || path.split('.').length == 1)
        return true;
    else
        return false;
}

function getCacheData(request, cacheName) {
    var storageUrl = request.url;

    var pathParams = storageUrl.split('/');

    var checkResponse = isItServerRequest(pathParams[pathParams.length - 1]);

    if (checkResponse) cacheName = serverCache;

    return caches.open(cacheName).then(function(cache) {
        if (checkResponse == true) {
            return fetch(request).then(function(networkResponse) {
                if (networkResponse.ok == true) {
                    cache.put(storageUrl, networkResponse.clone());
                }

                return networkResponse;
            }).catch(function(error) {
                return cache.match(storageUrl).then(function(response) {
                    if (response)
                        return formFilter(response);
                    else
                        return fallBackResponse('live');
                });
            });
        } else {
            return cache.match(storageUrl).then(function(response) {
                if (response) {
                    return response;
                } else {
                    return fetch(request).then(function(networkResponse) {
                        // if(networkResponse.ok == true){
                            cache.put(storageUrl, networkResponse.clone());
                        // }

                        return networkResponse;
                    }).catch(function(error) {
                        return fallBackResponse('css');
                    });
                }
            });
        }
    });
}

function fallBackResponse(type) {
    switch (type) {
        case 'post':
            var headers = {
                "Cache-Control": "no-cache",
                "Connection": "Keep-Alive",
                "Content-Length": "7960",
                "Content-Type": "application/json"
            }

            var body = {
                success: 'You are offline right now, we stored your data and will sync it as soon as you will get online.'
            };

            return new Response(JSON.stringify(body), { "headers": headers });

        case 'live':
            var headers = {
                "Cache-Control": "no-cache",
                "Connection": "Keep-Alive",
                "Content-Length": "7960",
                "Content-Type": "text/html"
            }

            var body = {
                offline: true
            };

            return new Response(JSON.stringify(body), { "headers": headers });

        case 'css':

        default:
            var headers = {
                "Cache-Control": "no-cache",
                "Connection": "Keep-Alive",
                "Content-Length": "7960",
                "Content-Type": "application/json"
            }

            var body = {};

            return new Response(JSON.stringify(body), { "headers": headers });
    }
}

function formFilter(response) {
    var headers = {
        "Cache-Control": "no-cache",
        "Connection": "Keep-Alive",
        "Content-Encoding": "gzip",
        "Content-Length": "7960",
        "Content-Type": "text/html; charset=UTF-8"
    }

    return response.text().then(function(body) {
        return new Response(body, { "headers": headers });
    });
}var version = 'v1';
var staticCache = 'bagisto-pwa-static-' + version;
var serverCache = 'bagisto-pwa-server-' + version;
var outSideCache = 'bagisto-pwa-outside-' + version;
var allCaches = [
    staticCache,
    serverCache,
    outSideCache
];

//add
var cacheFiles = [
];

//add scope for /blog and /article so that it can skip /customer scope
self.addEventListener('install', function(event) {
    console.log('[ServiceWorker] Install');

    event.waitUntil(
        caches.open(staticCache).then(function(cache) {
            return cache.addAll(cacheFiles);
        }).then(function() {
            self.skipWaiting();
        })
    );
});

self.addEventListener('activate', function(e) {
    console.log('[ServiceWorker] Activate');

    e.waitUntil(
        // Get all the cache keys (keyList)
        caches.keys().then(function(keyList) {
            return Promise.all(keyList.map(function(key) {
                // If a cached item is saved under a previous cacheName
                if (key !== staticCache && key !== serverCache && key !== outSideCache) {
                    // Delete that cached file
                    console.log('[ServiceWorker] Removing old cache', key);

                    return caches.delete(key);
                }
            }));
        })
    ); // end e.waitUntil

    return self.clients.claim();
});


self.addEventListener('fetch', function(event) {
    if (event.request.method != 'GET')
        return;

    var requestUrl = new URL(event.request.url);


    if (requestUrl.origin === location.origin) {
        if (skipIfThese(requestUrl))
            return;

        event.respondWith(getCacheData(event.request, staticCache));
    } else {
        event.respondWith(getCacheData(event.request, outSideCache));
    }
});

function skipIfThese(requestUrl) {
    if (requestUrl.pathname.indexOf("/pwa") < 0
        && requestUrl.pathname.indexOf("/mobile") < 0
        && requestUrl.pathname.indexOf("/storage") < 0
        && requestUrl.pathname.indexOf("/cache") < 0
        && requestUrl.pathname.indexOf("/api/") < 0
        && requestUrl.pathname.indexOf("/meduim-product-placeholder.png") < 0
        && requestUrl.pathname.indexOf("/small-product-placeholder.png") < 0
        && requestUrl.pathname.indexOf("/large-product-placeholder.png") < 0)
        return true;

    if (['/service-worker.js'].indexOf(requestUrl.pathname.pathname) > -1)
        return true;

    return false;
}

function isItServerRequest(path) {
    if (path == '' || path.split('.').length == 1)
        return true;
    else
        return false;
}

function getCacheData(request, cacheName) {
    var storageUrl = request.url;

    var pathParams = storageUrl.split('/');

    var checkResponse = isItServerRequest(pathParams[pathParams.length - 1]);

    if (checkResponse) cacheName = serverCache;

    return caches.open(cacheName).then(function(cache) {
        if (checkResponse == true) {
            return fetch(request).then(function(networkResponse) {
                if (networkResponse.ok == true) {
                    cache.put(storageUrl, networkResponse.clone());
                }

                return networkResponse;
            }).catch(function(error) {
                return cache.match(storageUrl).then(function(response) {
                    if (response)
                        return formFilter(response);
                    else
                        return fallBackResponse('live');
                });
            });
        } else {
            return cache.match(storageUrl).then(function(response) {
                if (response) {
                    return response;
                } else {
                    return fetch(request).then(function(networkResponse) {
                        // if(networkResponse.ok == true){
                            cache.put(storageUrl, networkResponse.clone());
                        // }

                        return networkResponse;
                    }).catch(function(error) {
                        return fallBackResponse('css');
                    });
                }
            });
        }
    });
}

function fallBackResponse(type) {
    switch (type) {
        case 'live':
            var headers = {
                "Cache-Control": "no-cache",
                "Connection": "Keep-Alive",
                "Content-Length": "7960",
                "Content-Type": "text/html"
            }

            var body = {
                offline: true
            };

            return new Response(JSON.stringify(body), { "headers": headers });

        case 'css':

        default:
            var headers = {
                "Cache-Control": "no-cache",
                "Connection": "Keep-Alive",
                "Content-Length": "7960",
                "Content-Type": "application/json"
            }

            var body = {};

            return new Response(JSON.stringify(body), { "headers": headers });
    }
}

function formFilter(response) {
    var headers = {
        "Cache-Control": "no-cache",
        "Connection": "Keep-Alive",
        "Content-Encoding": "gzip",
        "Content-Length": "7960",
        "Content-Type": "text/html; charset=UTF-8"
    }

    return response.text().then(function(body) {
        return new Response(body, { "headers": headers });
    });
}