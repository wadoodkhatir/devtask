import { createWebHistory, createRouter } from 'vue-router';
import home from './pages/home.vue';
import login from './pages/login.vue';
import register from './pages/register.vue';
import dashboard from './pages/dashboard.vue';
import forgot from './pages/forgot.vue';
import reset from './pages/reset.vue';
import verify from './pages/verify.vue';

import generate from './pages/generate.vue';

import getsurvay from './pages/getsurvay.vue';
import survay from './pages/survay.vue';
import store from './store'

const routes = [
    {
        path : '/',
        name : 'Home',
        component : home
    },
    {
        path : '/login',
        name : 'Login',
        component : login,
        meta:{
            requiresAuth:false
        }
    },
    {
        path : '/register',
        name : 'Register',
        component : register,
        meta:{
            requiresAuth:false
        }
    },
    {
        path : '/forgot',
        name : 'Forgot',
        component : forgot,
        meta:{
            requiresAuth:false
        }
    },
   
    {
        path : '/reset',
        name : 'Reset',
        component : reset,
        meta:{
            requiresAuth:false
        }
    },
    {
        path : '/verify',
        name : 'Verify',
        component : verify,
        meta:{
            requiresAuth:false
        }
    },
    
    {
        path : '/dashboard',
        name : 'Dashboard',
        component : dashboard,
        meta:{
            requiresAuth:true
        }
    },


    {
        path : '/survay',
        name : 'survay',
        component : survay,
       
    },

    {
        path : '/generate',
        name : 'Generate',
        component : generate,
        meta:{
            requiresAuth:true
        }
    },

    
    {
        path : '/getsurvay',
        name : 'Getsurvay',
        component : getsurvay,
        meta:{
            requiresAuth:true
        }
    },

    
];

const router = createRouter({
    history: createWebHistory(),
    routes
});


router.beforeEach((to,from) =>{
    if(to.meta.requiresAuth && store.getters.getToken == 0){
        return { name : 'Login'}
    }
    if(to.meta.requiresAuth == false && store.getters.getToken != 0){
        return { name : 'Dashboard'}
    }
})

export default router;
