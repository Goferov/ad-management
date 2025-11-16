import { ref, type Ref } from 'vue'
import { adService } from '../services/adService'
import type { AdStats, CreateAdPayload, PaginationMeta } from '../types/ad'

export function useAds() {
    const ads: Ref<AdStats[]> = ref([])
    const loading: Ref<boolean> = ref(false)
    const error: Ref<string | null> = ref(null)
    const pagination: Ref<PaginationMeta | null> = ref(null)

    const fetchAdStats = async (page: number = 1, perPage: number = 10): Promise<void> => {
        loading.value = true
        error.value = null
        try {
            const response = await adService.getAdStats(page, perPage)
            ads.value = response.data
            pagination.value = response.meta
        } catch (err) {
            error.value = 'Failed to fetch ad statistics'
            console.error('Error fetching ads:', err)
        } finally {
            loading.value = false
        }
    }

    const createAd = async (payload: CreateAdPayload): Promise<boolean> => {
        loading.value = true
        error.value = null
        try {
            await adService.createAd(payload)
            return true
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to create ad'
            console.error('Error creating ad:', err)
            return false
        } finally {
            loading.value = false
        }
    }

    return {
        ads,
        loading,
        error,
        pagination,
        fetchAdStats,
        createAd,
    }
}
