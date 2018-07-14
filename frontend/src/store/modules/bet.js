export default {
    namespaced: true,
    state: {
        bets: null,
    },
    mutations: {
        setBets(state, data) {
            state.bets = data;
        },
        setHomeScore(state, {id, score}) {
            state.bets[id].home_score = score;
        },
        setAwayScore(state, {id, score}) {
            state.bets[id].away_score = score;
        },
    },
    getters: {
        getBetById(state) {
            return (id) => {
                return state.bets[id];
            };
        },
        getBetByMatchAndUser(state, getters, rootState) {
            return (matchId, userId) => {
                let userBets = rootState.userModule.users[userId].bets_ids;
                let matchBets = rootState.matchModule.matches[matchId].bets_ids;

                for (let userBetId in userBets) {
                    if (userBets.hasOwnProperty(userBetId)) {
                        for (let matchBetId in matchBets) {
                            if (matchBets.hasOwnProperty(matchBetId) && userBetId === matchBetId) {
                                // could have been state.bets[userBets[userBetId]]
                                return state.bets[matchBets[matchBetId]];
                            }
                        }
                    }
                }

                return null;
            };
        },
    },
};
