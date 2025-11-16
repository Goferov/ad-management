export interface Ad {
    id: number
    title: string
    text: string
    image_url: string
    target_url: string
    created_at?: string
    updated_at?: string
}

export interface AdStats extends Ad {
    impressions: number
    clicks: number
    ctr: number
}

export interface CreateAdPayload {
    title: string
    text: string
    image: File
    target_url: string
}

export interface TrackEventPayload {
    ad_id: number
    type: 'impression' | 'click'
}

export interface ApiResponse<T> {
    data: T
}

export interface PaginationMeta {
    current_page: number
    from: number
    last_page: number
    per_page: number
    to: number
    total: number
}

export interface PaginatedResponse<T> {
    data: T[]
    links: {
        first: string
        last: string
        prev: string | null
        next: string | null
    }
    meta: PaginationMeta
}
