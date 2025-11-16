import apiClient from '../api/client'
import type { Ad, CreateAdPayload, ApiResponse, PaginatedResponse, AdStats } from '../types/ad'

export const adService = {
    async createAd(payload: CreateAdPayload): Promise<Ad> {
        const formData = new FormData()
        formData.append('title', payload.title)
        formData.append('text', payload.text)
        formData.append('image', payload.image)
        formData.append('target_url', payload.target_url)

        const response = await apiClient.post<ApiResponse<Ad>>('/ads', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })

        // jeśli backend zwraca { data: { ... } }, to tu zawsze dostaniesz Ad
        return response.data.data
    },

    async getAdById(id: number): Promise<Ad> {
        const response = await apiClient.get<ApiResponse<Ad>>(`/ads/${id}`)
        return response.data.data
    },

    async getAdStats(page: number = 1, perPage: number = 10): Promise<PaginatedResponse<AdStats>> {
        const response = await apiClient.get<PaginatedResponse<AdStats>>('/ad-stats', {
            params: { page, per_page: perPage },
        })
        return response.data
    },
}
