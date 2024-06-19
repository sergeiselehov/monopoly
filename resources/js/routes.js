import { createWebHistory, createRouter } from "vue-router";

//auto register components to vue router
const components = import.meta.glob('../components/*.vue', {eager: true})
let routes = [
    {
        path:'/login',
        name: 'login',
        component: ()=> import('../components/Login.vue'),
        meta: {hideForAuth: true},
    },
    {
        path:'/',
        name: 'home',
        component: ()=> import('../components/Home.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path:'/home',
        name: 'home',
        component: ()=> import('../components/Home.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path: '/merchant-data',
        name: 'merchant-data',
        component: ()=>import('../components/Merchant/MerchantData.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path: '/support',
        name: 'support',
        component: ()=>import('../components/Support.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path: '/transactions',
        name: 'transactions',
        component: ()=>import('../components/Transactions.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path: '/merchants',
        name: 'merchants',
        component: ()=>import('../components/Merchant.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path: '/account',
        name: 'account',
        component: ()=>import('../components/Account.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path: '/users',
        name: 'users',
        component: ()=>import('../components/User.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path: '/roles',
        name: 'roles',
        component: ()=>import('../components/Role.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path:'/balance',
        name: 'balance',
        component: ()=> import('../components/Balance.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path:'/withdrawls',
        name: 'withdrawls',
        component: ()=> import('../components/Withdrawls.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path:'/withdrawal-payments',
        name: 'withdrawal-payments',
        component: ()=> import('../components/WithdrawalPayments.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },
    {
        path:'/disputes',
        name: 'disputes',
        component: ()=> import('../components/Disputes.vue'),
        meta: {requiresAuth: true},
        beforeEnter: ifAuthenticated
    },

]


const router = createRouter({
    history: createWebHistory(),
    routes,
});

function ifAuthenticated  (to, from, next)  {
    if (localStorage.getItem("user")) {
        next();
        return;
    }
    location.href='/login'
}


export default router;
