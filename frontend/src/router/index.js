import Vue from "vue";
import VueRouter from "vue-router";
import Cookie from 'vue-cookie';
import store from "../store/index";

import MatchBetList from '../components/MatchBetList';
import MatchHistoryList from '../components/MatchHistoryList';
import RankList from '../components/RankList';
import Admin from '../components/Admin';
import LoginForm from '../components/LoginForm';

Vue.use(VueRouter);
Vue.use(Cookie);

const routes = [
    {name: 'bets', path: '/pronostics', component: MatchBetList,},
    {name: 'history', path: '/historique', component: MatchHistoryList,},
    {name: 'ranks', path: '/classement', component: RankList,},
    {name: 'admin', path: '/admin', component: Admin,},
    {name: 'logIn', path: '/login', component: LoginForm,},
    {name: 'logOut', path: '/logout',},
    {name: 'default', path: '/',},
];

const router = new VueRouter({
    routes,
    mode: 'history',
});

router.beforeEach((to, from, next) => {
    if (to.name === 'default' || (to.name === 'admin' && store.getters['authModule/isAdmin'] === false)) {
        next({name: 'bets'});
    } else if (to.name === 'logIn' || (to.name !== 'logOut' && store.getters['authModule/loggedIn'] === true)) {
        next();
    } else {
        store.dispatch('authModule/logOut');
        next({name: 'logIn'});
    }
});

export default router;
