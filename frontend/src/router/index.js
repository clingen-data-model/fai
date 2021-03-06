import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        name: 'Dashboard',
        path: '/',
        redirect: {name: 'FunctionalAssayIndex'}
        // component: () => import('@/views/Dashboard.vue'),
        // meta: {
        //     protected: true
        // }
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
    { name: 'AssayClassDetail',
        path: '/assay-classes/:id',
        component: () => import ('@/views/AssayClassDetail.vue'),
        meta: {
            protected: true
        },
        props: route => ({ id: parseInt(route.params.id)})
    },
    { name: 'AssayClassUpdate',
        path: '/assay-classes/:id/edit',
        component: () => import ('@/views/AssayClassUpdate.vue'),
        meta: {
            protected: true
        },
        props: route => ({ id: parseInt(route.params.id)})
    },
    { name: 'AssayClassDelete',
        path: '/assay-classes/:id/delete',
        component: () => import('@/views/AssayClassDelete.vue'),
        meta: {
            protected: true
        },
        props: route => ({ id: parseInt(route.params.id)})
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
        props: route => ({ id: parseInt(route.params.id)})
    },

    { name: 'CodingSystemDelete',
        path: '/coding-systems/:id/delete',
        component: () => import('@/views/CodingSystemDelete.vue'),
        meta: {
            protected: true
        },
        props: route => ({ id: parseInt(route.params.id)})
    },

    { name: 'PublicationIndex',
        path: '/publications',
        component: () => import('@/views/PublicationIndex.vue'),
        meta: {
            protected: true,
        },
    },

    { name: 'PublicationCreate',
        path: '/publications/create',
        component: () => import('@/views/PublicationCreate.vue'),
        meta: {
            protected: true,
        },
    },

    { name: 'PublicationUpdate',
        path: '/publications/:id/edit',
        component: () => import('@/views/PublicationUpdate.vue'),
        meta: {
            protected: true,
        },
        props: route => ({ id: parseInt(route.params.id)})
    },

    { name: 'PublicationDelete',
        path: '/publications/:id/delete',
        component: () => import('@/views/PublicationDelete.vue'),
        meta: {
            protected: true
        },
        props: route => ({ id: parseInt(route.params.id)})
    },

    {
        name: 'FunctionalAssayIndex',
        path: '/functional-assays',
        component: () => import('@/views/FunctionalAssayIndex.vue'),
        meta: {
            protected: true
        },
        props: true
    },
    {
        name: 'FunctionalAssayCreate',
        path: '/functional-assays/create',
        component: () => import('@/views/FunctionalAssayCreate.vue'),
        meta: {
            protected: true
        },
        props: true
    },
    {
        name: 'FunctionalAssayDetail',
        path: '/functional-assays/:id',
        component: () => import('@/views/FunctionalAssayDetail.vue'),
        meta: {
            protected: true
        },
        props: route => ({ id: parseInt(route.params.id)})
    },
    {
        name: 'FunctionalAssayEdit',
        path: '/functional-assays/:id/edit',
        component: () => import('@/views/FunctionalAssayUpdate.vue'),
        meta: {
            protected: true
        },
        props: route => ({ id: parseInt(route.params.id)})
    },

    { name: 'StyleGuide',
        path: '/dev/style-guide',
        component: () => import('@/views/dev/StyleGuide.vue'),
    },
    { name: 'FormExample',
        path: '/dev/form-example',
        component: () => import('@/views/dev/FormDefExample.vue'),
    },
    { name: 'PubMedTest',
        path: '/dev/pubmed',
        component: () => import('@/views/dev/PubMedTest.vue'),
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes
})


export default router
