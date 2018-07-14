import Vue from "vue";
import Cookie from 'vue-cookie';
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueAxios, axios);
Vue.use(Cookie);

export default {
    namespaced: true,
    state: {
        token: Vue.cookie.get('BEARER') || null,
    },
    getters: {
        payload(state) {
            if (state.token !== null && state.token !== undefined) {
                let splitToken = state.token.split('.');
                if (splitToken.length === 3) {
                    let payload = JSON.parse(window.atob(splitToken[1]));
                    if (payload.hasOwnProperty('exp')) {
                        let currentTimestamp = (new Date()).getTime() / 1000;
                        if (currentTimestamp < payload.exp) {
                            return payload;
                        }
                    }
                }
            }
            return null;
        },
        loggedIn(state, getters) {
            return getters.payload !== null && getters.payload !== undefined;
        },
        userId(state, getters) {
            return getters.loggedIn ? getters.payload.userId : null;
        },
        isAdmin(state, getters) {
            return getters.loggedIn
                ? getters.payload.hasOwnProperty('roles') && getters.payload.roles.hasOwnProperty('ROLE_ADMIN')
                : null;
        },
    },
    mutations: {
        setToken(state, token) {
            state.token = token;
        },
        clearToken(state) {
            state.token = null;
        },
    },
    actions: {
        logIn(context, {username, password}) {
            return new Promise((resolve, reject) => {
                Vue.axios.post(context.rootState.apiBaseUrl + '/login_check', {
                    username,
                    password
                }).then((response) => {
                    if (response.hasOwnProperty('status')
                        && response.status === 200
                        && response.hasOwnProperty('data')
                        && response.data.hasOwnProperty('token')
                    ) {
                        context.commit('setToken', response.data.token);
                        Vue.cookie.set('BEARER', response.data.token, {expires: 7});
                        resolve();
                    } else {
                        context.dispatch('logOut');
                        reject();
                    }
                }).catch(() => {
                    reject();
                });
            });
        },
        logOut(context) {
            context.commit('clearToken');
            Vue.cookie.delete('BEARER');
        },
        createAccount(context, {username, password}) {
            return new Promise((resolve, reject) => {
                Vue.axios.post(context.rootState.apiBaseUrl + '/users/new', {
                    username,
                    password
                }).then((response) => {
                    if (response.hasOwnProperty('status')
                        && response.status === 201
                        && response.hasOwnProperty('data')
                    ) {
                        resolve();
                    } else {
                        reject();
                    }
                }).catch(() => {
                    reject();
                });
            });
        },
    },
}
