<!-- src/views/CreateAdView.vue -->
<template>
    <div class="min-h-screen bg-gray-50">
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="flex items-center gap-4">
                    <router-link
                        to="/"
                        class="text-gray-600 hover:text-gray-900 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </router-link>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Ad</h1>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">
            <AdForm @created="handleCreated" />

            <div
                v-if="lastCreatedAd"
                class="bg-white rounded-lg shadow p-6"
            >
                <h2 class="text-xl font-bold mb-2">Embed code</h2>
                <p class="text-sm text-gray-500 mb-2">
                    Paste this snippet into any page where you want to show this ad.
                </p>

                <pre class="bg-gray-900 text-gray-100 text-sm p-3 rounded-lg overflow-x-auto">
{{ embedCode }}
        </pre>
            </div>
        </main>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import AdForm from '../components/AdForm.vue'
import type { Ad } from '../types/ad'

const lastCreatedAd = ref<Ad | null>(null)

const scriptUrl =
    import.meta.env.VITE_AD_SCRIPT_URL || 'http://localhost:8000/ad/scripts.js'

const embedCode = computed(() => {
    if (!lastCreatedAd.value) return ''

    return `<script src="${scriptUrl}" data-ad-id="${lastCreatedAd.value.id}"><\/script>`
})


function handleCreated(ad: Ad) {
lastCreatedAd.value = ad
}
</script>
