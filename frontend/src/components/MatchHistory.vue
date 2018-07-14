<template>
    <div class="col-12 col-md-6 col-lg-4 mb-4">
        <div class="card" :class="[ match.is_over ? 'border-secondary' : 'border-success' ]">
            <div class="card-header text-center text-white bk-match-card-header"
                 :class="[ match.is_over ? 'bg-secondary' : 'bg-success' ]">
                {{ match.kick_off }}
            </div>
            <div class="card-body text-info bk-match-card-content">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-center bk-match-card-content">
                        <div class="col-4">
                            <div class="">
                                <img class="img-fluid" :src="flagsUrl + match.ht_abbr"/>
                            </div>
                            <div class="text-center bk-team-name text-uppercase">
                                {{ match.ht_abbr }}
                            </div>
                        </div>
                        <div class="col-4 text-center bk-score">
                            <span v-if="match.is_over">{{ match.home_score }} - {{ match.away_score }}</span>
                            <font-awesome-icon icon="futbol" class="fa-lg text-success" v-else></font-awesome-icon>
                        </div>
                        <div class="col-4">
                            <div class="">
                                <img class="img-fluid" :src="flagsUrl + match.at_abbr"/>
                            </div>
                            <div class="text-center bk-team-name text-uppercase">
                                {{ match.at_abbr }}
                            </div>
                        </div>
                    </div>

                    <div v-if="match.hasOwnProperty('bets') && match.bets.length > 0" class="row bk-bets">
                        <div class="col">
                            <table class="table table-sm text-secondary">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">
                                        <font-awesome-icon icon="trophy"></font-awesome-icon>
                                    </th>
                                    <th class="text-right">
                                        <font-awesome-icon icon="balance-scale"></font-awesome-icon>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <bet v-for="bet in orderedBets" :bet="bet" :key="bet.id" :match="match"></bet>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Bet from './Bet';
    import {mapState} from 'vuex';


    export default {
        name: "MatchHistory",
        props: ['match'],
        components: {Bet},
        methods: {
            compareBets(a, b) {
                let signA = Math.sign(a.home_score - a.away_score);
                let signB = Math.sign(b.home_score - b.away_score);
                if (a.points !== b.points) { // sort by points descending first
                    return b.points - a.points;
                } else if (signA !== signB) { // then by 1N2 prediction (1 > N > 2)
                    return signB - signA;
                } else if (a.home_score !== b.home_score) { // then by home score prediction
                    return b.home_score - a.home_score;
                } else if (a.away_score !== b.away_score) { // then by away score prediction
                    return b.away_score - a.away_score;
                } else if (a.username > b.username) { // then by name
                    return 1;
                } else if (a.username < b.username) {
                    return -1;
                } else {
                    return 0;
                }
            },
        },
        computed: Object.assign(
            mapState('matchModule', ['flagsUrl']),
            {
                orderedBets() {
                    return this.match.bets.sort(this.compareBets);
                }
            }
        ),
    }
</script>

<style scoped>

</style>
