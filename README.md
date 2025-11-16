# Implementation overview

> TL;DR: the required “advertisement code” lives in `public/ad/index.html` and `public/ad/scripts.js`.  
> The dashboard and ad creation form are implemented as a standalone Vue app in the `frontend` directory.

---

## Where to find `index.html` and `scripts.js`

The two files required by the assignment are located here:

- `public/ad/index.html`  
  A simple static page used as an **ad preview**.  
  It loads the widget like this:

  ```html
  <div id="ad-root"></div>
  <script src="/ad/scripts.js" data-ad-id="1"></script>
  ```

  You can change `data-ad-id` to any existing ad ID created via the dashboard.

- `public/ad/scripts.js`  
  A standalone, framework‑agnostic JavaScript widget that:

    - fetches ad data from the backend API (`GET /api/ads/{id}`),
    - renders the ad (image + text + link),
    - injects its own CSS styles into the page,
    - tracks:
        - **impressions** – on render (`POST /api/track` with type `impression`),
        - **clicks** – when the user clicks the image or button (`POST /api/track` with type `click`).

The widget automatically detects the `<script>` tag that loaded it (`<script src=".../ad/scripts.js" data-ad-id="...">`), creates a container if needed, and renders the ad inside it.

### Using the widget on any external page

To display an ad on any page, you only need to include:

```html
<script src="https://localhost/ad/scripts.js" data-ad-id="AD_ID"></script>
```

- `src` points to `public/ad/scripts.js` served by the Laravel app,
- `data-ad-id` is the ID of an ad created via the dashboard.

The widget will handle fetching data, rendering, styling and tracking automatically.

---

## Dashboard & ad creation (frontend)

The dashboard and ad creation flow are implemented as a separate Vue application in the `frontend` directory (as required by the task – Laravel’s built‑in Vite setup is not used).

- **Dashboard**:
    - lists ads in a table with pagination,
    - shows per‑ad stats (impressions, clicks, CTR),
    - provides an action to **copy the embed code** for a given ad.

- **Create Ad** view:
    - form fields: title, description text, image upload, target URL,
    - client‑side validation (required fields, URL format, file type & size),
    - on success:
        - the newly created ad ID is shown,
        - a ready‑to‑paste embed snippet is displayed, e.g.:

          ```html
          <script src="https://localhost/ad/scripts.js" data-ad-id="NEW_AD_ID"></script>
          ```

This makes it easy to create an ad via the UI and immediately use it on any external page.

---
---
---
---
---
---

# fullstack-task-2025

# Setup

Download an example project and follow the `readme.md` file to set up the project. Only Docker is required to get the stack up and running.

---

# Assignment

Create a simple module that receives input from the user (**image** and **text**) and outputs it as a **combined advertisement code**.
The code should consist of **2 files**:

- `index.html`
- `scripts.js`

### index.html
Should contain the HTML part of the built advertisement that embeds the content created by the user, as well as the styles.

### scripts.js
Should contain:
- The logic for displaying the advertisement.
- Tracking of clicks on the image by sending a tracking event to the endpoint.
- Tracking of impressions (advertisement displays).

### Dashboard Endpoint
Prepare an endpoint that presents advertisement data in a simple table (`/dashboard`).

---

# Frontend

You can use any CSS framework of your choice, **or none at all**.
**Do not** use the Laravel Vite setup — instead, use a **standalone Vue application** inside the `frontend` directory.

---

# Tips

Remember that this is a test assignment.
What matters most is to **showcase your knowledge**, not to create a perfect, production-grade application

---

# Project setup

1. Run `make init`
2. Run `make build`
3. Run `make up ARGS="-d"` to start the containers.
4. Run `make setup` to initialize defaults.

Sometimes you may have to wait until mariadb intializes properly. If you get an error on `make setup`, retry it in 5 minutes.

## Development

1. Run `make exec app_bash` to enter application container
2. Run `make exec frontend_bash` to enter frontend container

### HINT

To pass arguments, use ARGS like this:
`make up ARGS="-d"`
`make logs ARGS="app"`
