<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
    <div class="relative">
      <img 
        :src="ad.image_url" 
        :alt="ad.title"
        class="w-full h-48 object-cover"
        @error="handleImageError"
      />
      <div class="absolute top-2 right-2 bg-black/70 text-white px-2 py-1 rounded text-xs">
        ID: {{ ad.id }}
      </div>
    </div>
    
    <div class="p-4">
      <h3 class="text-xl font-bold text-gray-800 mb-2">{{ ad.title }}</h3>
      <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ ad.text }}</p>
      
      <div class="flex items-center gap-4 mb-4 text-sm">
        <div class="flex items-center gap-1">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
          <span class="font-semibold">{{ ad.impressions || 0 }}</span>
          <span class="text-gray-500">views</span>
        </div>
        
        <div class="flex items-center gap-1">
          <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
          </svg>
          <span class="font-semibold">{{ ad.clicks || 0 }}</span>
          <span class="text-gray-500">clicks</span>
        </div>
        
        <div class="ml-auto">
          <span class="text-xs text-gray-500">CTR:</span>
          <span class="font-bold text-purple-600">{{ ctr }}%</span>
        </div>
      </div>
      
      <a 
        :href="ad.target_url" 
        target="_blank"
        @click="handleClick"
        class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors font-medium"
      >
        View Ad →
      </a>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { trackingService } from '@/services/trackingService'
import type { AdStats } from '@/types/ad'

interface Props {
  ad: AdStats
}

const props = defineProps<Props>()
const emit = defineEmits<{
  impression: [adId: number]
  click: [adId: number]
}>()

const ctr = computed(() => {
  if (!props.ad.impressions || props.ad.impressions === 0) return '0.00'
  return ((props.ad.clicks / props.ad.impressions) * 100).toFixed(2)
})

const handleClick = async () => {
  try {
    await trackingService.trackClick(props.ad.id)
    emit('click', props.ad.id)
  } catch (error) {
    console.error('Error tracking click:', error)
  }
}

const handleImageError = (event) => {
  event.target.src = '/placeholder.svg?height=200&width=400'
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
