import apiClient from '../api/client'
import type { TrackEventPayload } from '../types/ad'

export const trackingService = {
  async trackEvent(payload: TrackEventPayload): Promise<void> {
    await apiClient.post('/track', payload)
  },

  async trackImpression(adId: number): Promise<void> {
    await this.trackEvent({ ad_id: adId, type: 'impression' })
  },

  async trackClick(adId: number): Promise<void> {
    await this.trackEvent({ ad_id: adId, type: 'click' })
  },
}
