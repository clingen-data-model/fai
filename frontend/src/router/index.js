import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        name: 'Dashboard',
        path: '/',
        component: () => import('@/views/Dashboard.vue'),
        meta: {
            protected: true
        }
    },
    { name: 'AssayClassIndex',
        path: '/assay-classes',
        component: () => import('@/views/AssayClassIndex.vue'),
        meta: {
            protected: true
        }
    },
    { name: 'AssayClassCreate',
        path: '/assay-classes/create',
        component: () => import('@/views/AssayClassCreate.vue'),
        meta: {
            protected: true
        }
    },
    { name: 'AssayClassUpdate',
        path: '/assay-classes/:id/edit',
        component: () => import ('@/views/AssayClassUpdate.vue'),
        meta: {
            protected: true
        },
        props: true

    },
    { name: 'AssayClassDelete',
        path: '/assay-classes/:id/delete',
        component: () => import('@/views/AssayClassDelete.vue'),
        meta: {
            protected: true
        },
        props: true
    },

    { name: 'CodingSystemIndex',
        path: '/coding-systems',
        component: () => import('@/views/CodingSystemIndex.vue'),
        meta: {
            protected: true,
            permissions: ['coding-systems-create']
        },
    },

    { name: 'CodingSystemCreate',
        path: '/coding-systems/create',
        component: () => import('@/views/CodingSystemCreate.vue'),
        meta: {
            protected: true,
            permissions: ['coding-systems-create']
        },
    },

    { name: 'CodingSystemUpdate',
        path: '/coding-systems/:id/edit',
        component: () => import('@/views/CodingSystemUpdate.vue'),
        meta: {
            protected: true,
            permissions: ['coding-systems-update']
        },
        props: true
    },

    { name: 'CodingSystemDelete',
        path: '/coding-system/:id/delete',
        component: () => import('@/views/CodingSystemDelete.vue'),
        meta: {
            protected: true
        },
        props: true
    },

    { name: 'StyleGuid',
        path: '/dev/style-guide',
        component: () => import('@/views/dev/StyleGuide.vue'),
    }
];


const router = createRouter({
    history: createWebHistory(),
    routes
})


export default router