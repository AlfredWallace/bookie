export default {
    namespaced: true,
    state: {
        matches: null,
        flagsUrl: 'https://fsprdcdnpublic.azureedge.net/global-pictures/flags-fwc2018-4/',
    },
    mutations: {
        setMatches(state, data) {
            state.matches = data;
        },
        setHomeScore(state, {id, score}) {
            state.matches[id].home_score = score;
        },
        setAwayScore(state, {id, score}) {
            state.matches[id].away_score = score;
        },
    },
    getters: {
        getMatch(state) {
            return (id) => {
                return state.matches[id];
            };
        },
    },
};
