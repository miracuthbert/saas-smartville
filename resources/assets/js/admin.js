import pluralize from './mixins/pluralize'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);

import VueTimeago from 'vue-timeago';
Vue.use(VueTimeago, {
    name: 'timeago', // component name, `timeago` by default
    locale: 'en-US',
    locales: {
        // you will need json-loader in webpack 1
        'en-US': require('vue-timeago/locales/en-US.json')
    }
});

var VueScrollTo = require('vue-scrollto');
Vue.use(VueScrollTo);

// import TagsIndex from './components/tags/Index';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    'data-table',
    require('./components/DataTable.vue')
);

Vue.component(
    'price-input',
    require('./components/form/PriceInput.vue')
);

// Vue.component('tags-index', TagsIndex);

Vue.component(
    'property-image-upload',
    require('./components/tenant/properties/ImageUpload.vue')
);

Vue.component(
    'tenant-property-features-index',
    require('./components/tenant/properties/features/Index.vue')
);

/**
 * Mixins
 */
Vue.mixin(pluralize)

const app = new Vue({
    el: '#app'
});

// Custom Js
require('./admin/custom')
