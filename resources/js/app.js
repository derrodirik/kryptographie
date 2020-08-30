/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import BootstrapVue from 'bootstrap-vue'
import VueClipboard from 'vue-clipboard2'

Vue.use(VueRouter)
Vue.use(BootstrapVue)
Vue.use(VueClipboard)

import App from '../assets/js/views/App'
import Caesar from '../assets/js/views/Caesar'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            redirect: {
                name: 'caesar'
            }
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
