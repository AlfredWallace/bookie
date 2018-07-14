import Vue from 'vue';
import App from './App';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Notifications from 'vue-notification';
import fontawesome from '@fortawesome/fontawesome';
import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
import solid from '@fortawesome/fontawesome-free-solid';
import './scss/bookie.scss';
import store from './store/index';
import router from './router/index';
import Cookie from 'vue-cookie';

fontawesome.library.add(solid);

Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.use(Notifications);
Vue.use(Cookie);
Vue.use(VueAxios, axios);

new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: {App},
});
