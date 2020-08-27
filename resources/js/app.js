/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import BootstrapVue from 'bootstrap-vue'

Vue.use(VueRouter)
Vue.use(BootstrapVue)

import App from '../assets/js/views/App'
import Home from '../assets/js/views/Home'
import Caesar from '../assets/js/views/Caesar'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/caesar',
            name: 'caesar',
            component: Caesar,
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
