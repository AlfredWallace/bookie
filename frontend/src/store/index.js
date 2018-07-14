import Vue from "vue";
import Vuex from "vuex";
import matchModule from './modules/match';
import userModule from './modules/user';
import betModule from './modules/bet';
import teamModule from './modules/team';
import authModule from './modules/auth';
import router from "../router";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        matchModule,
        userModule,
        betModule,
        teamModule,
        authModule,
    },
    state: {
        apiBaseUrl: process.env.BOOKIE_API_URL,
    },
    actions: {
        fetchData(context, {url, setter}) {
            Vue.axios.get(context.state.apiBaseUrl + url, {
                headers: {
                    Authorization: `Bearer ${context.state.authModule.token}`,
                },
            }).then((response) => {
                if (response.hasOwnProperty('data')) {
                    context.commit(setter, response.data);
                }
            }).catch(() => {
                router.push({name: 'logOut'});
            });
        },
        fetchAll(context) {
            context.dispatch('fetchData', {url: '/users', setter: 'userModule/setUsers'});
            context.dispatch('fetchData', {url: '/teams', setter: 'teamModule/setTeams'});
            context.dispatch('fetchData', {url: '/bets', setter: 'betModule/setBets'});
            context.dispatch('fetchData', {url: '/matches', setter: 'matchModule/setMatches'});
        },
    },
});
