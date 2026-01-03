# GETA Water Bill System - PWA Documentation

This document outlines the steps taken to implement Progressive Web App (PWA) functionality into the GETA system and provides instructions for future maintenance.

## 1. PWA Components

### A. Web App Manifest (`public/manifest.json`)
The manifest file tells the browser how your app should behave when installed on a device.
- **`name`**: Full name of the app.
- **`short_name`**: Name shown under the icon on the home screen.
- **`start_url`**: The page that opens when the app starts.
- **`display: standalone`**: Removes the browser address bar for a native app feel.
- **`theme_color`**: Matches the branding (OrangeRed).

### B. Service Worker (`public/sw.js`)
The Service Worker handles background tasks and caching.
- **Caching Strategy**: Network-First. It tries to get the latest data from the server, but falls back to the cache if the user is offline.
- **Pre-caching**: Automatically saves CSS, JS, and essential pages for instant loading.

### C. App Icon (`public/pwa_icon.svg`)
A high-quality vector icon that represents the GETA brand on device home screens.

## 2. Implementation Steps

1.  **Created `manifest.json`**: Defined app identity and theme colors.
2.  **Created `sw.js`**: Implemented the caching logic.
3.  **Created `pwa_icon.svg`**: Designed a branded water-drop icon.
4.  **Updated `layouts/app.blade.php`**:
    - Linked the manifest file in the `<head>`.
    - Set the `theme-color` meta tag.
    - Added the JavaScript snippet to register the service worker on page load.

## 3. How to Use/Install

1.  **On Desktop (Chrome/Edge)**: Look for the "Install" icon (a computer with an arrow) in the right side of the address bar.
2.  **On Mobile (Android)**: A prompt "Add to Home Screen" will appear, or you can find it in the browser menu.
3.  **On Mobile (iOS)**: Tap the "Share" button in Safari and select "Add to Home Screen".

## 4. Troubleshooting & Updates

> [!TIP]
> **Updating the App**: If you make changes to the CSS or JS, you should update the `CACHE_NAME` in `sw.js` (e.g., change `v1` to `v2`) to force the browser to clear the old cache and download the new version.

---
*Documentation generated on 2026-01-03 for GETA Water Bill Management System.*
