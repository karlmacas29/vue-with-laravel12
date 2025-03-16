import {createRouter, createWebHistory} from 'vue-router'

import productIndex from '@/components/products/index.vue'
import notFound from '@/components/NotFound.vue'
import NProgress from "nprogress";
import "nprogress/nprogress.css"; // Import default styles

const routes = [
    {
        path: '/',
        name: 'products.index',
        component: productIndex
    },
    {
        path: '/products/:id/edit',
        name: 'products.edit',
        component: () => import('@/components/products/EditProducts.vue')
    },
    {
        //this is a 404 page not found
        path:'/:pathMatch(.*)*',
        name: 'notfound',
        component: notFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// 🌟 Add NProgress hooks for route changes
router.beforeEach((to, from, next) => {
    NProgress.start(); // Start progress bar
    next();
  });
  
  router.afterEach(() => {
    NProgress.done(); // Finish progress bar
  });

export default router