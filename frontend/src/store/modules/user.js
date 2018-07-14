export default {
    namespaced: true,
    state: {
        users: null,
    },
    mutations: {
        setUsers(state, data) {
            state.users = data;
        }
    },
};
