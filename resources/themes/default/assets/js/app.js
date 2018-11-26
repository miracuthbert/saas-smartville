import pluralize from '../../../../assets/js/mixins/pluralize'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../../../../assets/js/bootstrap');

window.Vue = require('vue');

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
Vue.use(VueScrollTo)

// Use v-calendar, v-date-picker & v-popover components
// import VCalendar from 'v-calendar';
// Vue.use(VCalendar)

// flatpickr vue component
// import flatPickr from 'vue-flatpickr-component';
// Vue.component('flatPickr', flatPickr);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    'passport-clients',
    require('../../../../assets/js/components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('../../../../assets/js/components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('../../../../assets/js/components/passport/PersonalAccessTokens.vue')
);

Vue.component(
    'avatar-upload',
    require('../../../../assets/js/components/account/avatar/AvatarUpload.vue')
);

Vue.component(
    'notifications',
    require('../../../../assets/js/components/notifications/Notifications.vue')
);

Vue.component(
    'notification-badge',
    require('../../../../assets/js/components/notifications/NotificationBadge.vue')
);

Vue.component(
    'contact',
    require('../../../../assets/js/components/contact/Contact.vue')
);

/**
 * Mixins
 */
Vue.mixin(pluralize)

const app = new Vue({
    el: '#app'
});

/**
 * Custom js
 */
require('../../../../assets/js/frontend/custom')
