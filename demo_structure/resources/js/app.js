
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import iView from 'iview';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import App from './app.vue';
import Routers from './router.js';
import MainMenu from './navigation/main_menu/main_menu.js';
import 'iview/dist/styles/iview.css';

window.Vue = require('vue');
window.Vuex = require('vuex');
window.iView = require('iview');

Vue.use(VueRouter);
Vue.use(iView);

//
//tmp
var util = {};
util.title = function (title) {
    title = title ? title + ' - Home' : 'iView project';
    window.document.title = title;
};
//
//


//
//router

//initialize router
const RouterConfig = {
    mode: 'history',
    routes: Routers
};
const router = new VueRouter(RouterConfig);

//set intial states
router.beforeEach((to, from, next) => {
    iView.LoadingBar.start();
    util.title(to.meta.title);
    next();
});

router.afterEach((to, from, next) => {
    iView.LoadingBar.finish();
    window.scrollTo(0, 0);
});


//
//state management

const store = new Vuex.Store({
    state: {
        base_url: 'http://51.158.69.132/demo_omp_john/public/',
        base_relative_url: '/demo_omp_john/public/',
        storage_url: 'http://51.158.69.132/demo_omp_john/public/storage/app/',
        navigation: {
            main_menu: MainMenu,
        }
    },
    modules: {
        //
        // are register inside every single file component
        //
    },
    mutations: {    //must be synchronous!! ta "actions" einai workflows praktika!!
        increment (state) {
            state.count++;
        }
    }
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('app-layout', require('./components/BasicLayout.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: { App },
    router: router,
    store
});
