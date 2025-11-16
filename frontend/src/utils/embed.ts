const AD_SCRIPT_URL =
    import.meta.env.VITE_AD_SCRIPT_URL || 'https://localhost/ad/scripts.js'

export function getAdScriptUrl(): string {
    return AD_SCRIPT_URL
}

export function getEmbedCode(adId: number | string): string {
    return `<script src="${AD_SCRIPT_URL}" data-ad-id="${adId}"></script>`
}
