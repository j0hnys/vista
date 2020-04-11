
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import ViewUI from 'view-design';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import App from './app.vue';
import Routers from './router.js';
import MainMenu from './navigation/main_menu/main_menu.js';
import 'iview/dist/styles/iview.css';

window.Vue = require('vue');
window.Vuex = require('vuex');
window.ViewUI = require('view-design');

Vue.use(VueRouter);
Vue.use(ViewUI);

//
//tmp
var util = {};
util.title = function (title) {
    title = title ? title + ' - Home' : 'ViewUI project';
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
    ViewUI.LoadingBar.start();
    util.title(to.meta.title);
    next();
});

router.afterEach((to, from, next) => {
    ViewUI.LoadingBar.finish();
    window.scrollTo(0, 0);
});


//
//state management

const store = new Vuex.Store({
    state: {
        base_url: process.env.MIX_BASE_URL+'/',
        base_relative_url: process.env.MIX_BASE_RELATIVE_URL+'/',
        storage_url: process.env.MIX_STORAGE_URL+'/',
        navigation: {
            main_menu: MainMenu,
        }
    },
    modules: {
        pages: {
            namespaced: true,
            //
            // page modules are register inside every page single file component
            //
        }
    },
    mutations: {    //must be synchronous!! ta "actions" einai workflows praktika!!
        increment (state) {
            state.count++;
        }
    },    
    getters: {
        navigation: (state) => (data) => {
            // console.log(data);
            return state.navigation;
        }
    },
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


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: { App },
    router: router,
    store,
    render: h => h(App)
});
