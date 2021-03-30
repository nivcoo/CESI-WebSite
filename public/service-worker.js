const cacheName = "cache-v1";
const assets = [
  "/",
  " ",
  "/panel",
  "/panel/users/student",
  "/panel/users/delegate",
  "/panel/users/pilote",
  "/panel/users/admin",
  "/panel/societies",
  "/panel/offers",
  "/panel/applications",
  "/panel/personal/wishlist",
  "/panel/personal/notification",
  "/panel/permissions",
];

// mettre en cache
self.addEventListener("install", (e) => {
  e.waitUntil(
    caches.open(cacheName).then((cache) => {
      cache.addAll(assets);
    })
  );
});

// fetcher le cache
self.addEventListener("fetch", (e) => {
  console.log(e.request);
  e.respondWith(
    caches.match(e.request).then((cache) => {
      return cache || fetch(e.request);
    })
  );
});

importScripts('sw-toolbox.js');