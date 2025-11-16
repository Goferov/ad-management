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
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                    </router-link>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Ad</h1>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">
            <!-- EMBED BOX – mocno widoczny po stworzeniu reklamy -->
            <section
                v-if="lastCreatedAd"
                ref="embedSection"
                class="bg-white rounded-lg shadow p-6 border border-green-200"
            >
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-green-800">
                            Ad created successfully (ID: {{ lastCreatedAd.id }})
                        </h2>
                        <p class="text-sm text-green-700 mt-1">
                            Copy this embed snippet and paste it into any page where you want to display this ad.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="copyEmbed"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium
                     bg-green-600 hover:bg-green-700 text-white transition-colors"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" stroke-width="2" />
                                <path
                                    stroke-width="2"
                                    d="M5 15H4a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h9a2 2 0 0 1 2 2v1"
                                />
                            </svg>
                            Copy embed code
                        </button>

                        <span
                            v-if="copied"
                            class="text-xs font-medium text-green-700"
                        >
              Copied!
            </span>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">
                        Embed code
                    </label>
                    <pre
                        class="bg-gray-900 text-gray-100 text-sm p-3 rounded-lg overflow-x-auto"
                    ><code>{{ embedCode }}</code></pre>
                </div>
            </section>

            <AdForm @created="handleCreated" />
        </main>
    </div>
</template>

<script setup lang="ts">
import { computed, nextTick, ref } from 'vue'
import AdForm from '../components/AdForm.vue'
import type { Ad } from '../types/ad'
import { getEmbedCode } from '../utils/embed'

const lastCreatedAd = ref<Ad | null>(null)
const embedSection = ref<HTMLElement | null>(null)
const copied = ref(false)

const embedCode = computed(() => {
    return lastCreatedAd.value ? getEmbedCode(lastCreatedAd.value.id) : ''
})

async function handleCreated(ad: Ad) {
    lastCreatedAd.value = ad
    copied.value = false

    await nextTick()
    if (embedSection.value) {
        embedSection.value.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
}

async function copyEmbed() {
    if (!embedCode.value) return

    try {
        if (navigator.clipboard?.writeText) {
            await navigator.clipboard.writeText(embedCode.value)
        } else {
            const textarea = document.createElement('textarea')
            textarea.value = embedCode.value
            textarea.setAttribute('readonly', '')
            textarea.style.position = 'absolute'
            textarea.style.left = '-9999px'
            document.body.appendChild(textarea)
            textarea.select()
            document.execCommand('copy')
            document.body.removeChild(textarea)
        }

        copied.value = true
        setTimeout(() => (copied.value = false), 2000)
    } catch (e) {
        console.error('Failed to copy embed code', e)
    }
}
</script>
