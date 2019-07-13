import Vue from 'vue';
import Vuex from 'vuex';
import MainMenu from './navigation/main_menu/main_menu.js';
import Index from './store/pages/Index.js';

Vue.use(Vuex);

window.Vuex = require('vuex');

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
            modules: {
                Index: Index,
            }
        }
    },
    getters: {
        navigation: (state) => (data) => {
            // console.log(data);
            return state.navigation;
        }
    },
});

export default store;