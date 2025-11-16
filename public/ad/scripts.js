(function () {
    const findHostingScript = () => {
        const scripts = document.getElementsByTagName('script');
        for (const s of scripts) {
            if (s.src && s.src.indexOf('/ad/scripts.js') !== -1) {
                return s;
            }
        }
        return null;
    };

    const scriptTag = findHostingScript();
    if (!scriptTag) {
        console.warn('[ad-widget] Could not find hosting <script> tag.');
        return;
    }

    const adId = scriptTag.getAttribute('data-ad-id');
    if (!adId) {
        console.warn('[ad-widget] Missing data-ad-id attribute on <script>.');
        return;
    }

    const scriptUrl = new URL(scriptTag.src, window.location.href);
    const baseOrigin = scriptUrl.origin;

    const API_BASE = window.AD_WIDGET_API_BASE || baseOrigin + '/api';

    console.log('[ad-widget] Using API_BASE =', API_BASE);

    const containerId = scriptTag.getAttribute('data-container-id') || 'ad-root';
    let root = document.getElementById(containerId);

    if (!root) {
        root = document.createElement('div');
        root.id = containerId;
        scriptTag.parentNode.insertBefore(root, scriptTag);
    }

    const injectStyles = () => {
        const styleId = 'ad-widget-styles';
        if (document.getElementById(styleId)) return;

        const css = `
      .ad-card {
        max-width: 320px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 12px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        background: #ffffff;
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.15);
      }

      .ad-image {
        display: block;
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 8px;
        object-fit: cover;
      }

      .ad-title {
        font-size: 1rem;
        font-weight: 600;
        margin: 4px 0;
        color: #111827;
      }

      .ad-text {
        font-size: 0.875rem;
        color: #4b5563;
        margin-bottom: 8px;
      }

      .ad-button {
        display: inline-block;
        font-size: 0.875rem;
        padding: 6px 12px;
        border-radius: 9999px;
        border: 1px solid #111827;
        color: #111827;
        text-decoration: none;
      }
    `;

        const style = document.createElement('style');
        style.id = styleId;
        style.type = 'text/css';
        style.appendChild(document.createTextNode(css));
        document.head.appendChild(style);
    };

    injectStyles();

    const track = (type) => {
        const payload = JSON.stringify({
            ad_id: Number(adId),
            type,
        });

        try {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', API_BASE + '/track', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    console.log('[ad-widget] track', type, 'status:', xhr.status);
                }
            };
            xhr.send(payload);
        } catch (e) {
            console.warn('[ad-widget] Failed to send tracking event', e);
        }
    };

    const renderAd = (ad) => {
        root.innerHTML = '';

        const card = document.createElement('div');
        card.className = 'ad-card';

        const link = document.createElement('a');
        link.href = ad.target_url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';

        const img = document.createElement('img');
        img.className = 'ad-image';
        img.src = ad.image_url;
        img.alt = ad.title || '';

        link.appendChild(img);

        const title = document.createElement('div');
        title.className = 'ad-title';
        title.textContent = ad.title || '';

        const text = document.createElement('div');
        text.className = 'ad-text';
        text.textContent = ad.text || '';

        const button = document.createElement('a');
        button.className = 'ad-button';
        button.href = ad.target_url;
        button.target = '_blank';
        button.rel = 'noopener noreferrer';
        button.textContent = 'Learn more';

        card.appendChild(link);
        card.appendChild(title);
        card.appendChild(text);
        card.appendChild(button);

        root.appendChild(card);

        track('impression');

        const handleClick = () => track('click');
        img.addEventListener('click', handleClick);
        button.addEventListener('click', handleClick);
    };

    const fetchAd = () => {
        const url = API_BASE + '/ads/' + adId;
        console.log('[ad-widget] Fetching ad from', url);

        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = () => {
            if (xhr.readyState !== 4) return;

            console.log('[ad-widget] GET status:', xhr.status);

            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    const ad = response.data || response;
                    renderAd(ad);
                } catch (e) {
                    console.error('[ad-widget] Failed to parse response', e);
                }
            } else {
                console.error('[ad-widget] Failed to fetch ad. Status:', xhr.status);
            }
        };
        xhr.onerror = () => {
            console.error('[ad-widget] Network error while fetching ad.');
        };
        xhr.send();
    };

    fetchAd();
})();
