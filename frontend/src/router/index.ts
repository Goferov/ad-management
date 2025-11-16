import { createRouter, createWebHistory } from 'vue-router'
import DashboardView from '../views/DashboardView.vue'
import CreateAdView from '../views/CreateAdView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            redirect: '/dashboard',
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: DashboardView
        },
        {
            path: '/create',
            name: 'create-ad',
            component: CreateAdView
        }
    ]
})

export default router
