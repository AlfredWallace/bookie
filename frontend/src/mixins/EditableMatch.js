import {mapGetters, mapState} from 'vuex';

export default {
    data() {
        return {
            loading: false
        };
    },
    props: ['matchId'],
    computed: Object.assign(
        mapState(['apiBaseUrl', 'auth']),
        mapState('matchModule', ['flagsUrl']),
        mapGetters('teamModule', ['getTeam']),
        mapGetters('matchModule', ['getMatch']),
        {
            match() {
                return this.getMatch(this.matchId);
            },
            homeTeam() {
                return this.getTeam(this.match.home_team_id);
            },
            awayTeam() {
                return this.getTeam(this.match.away_team_id);
            },
        }
    ),
    methods: {
        saveResult() {
            this.loading = true;
            this.axios.post(this.apiBaseUrl + this.saveResultPath,
                {
                    home_score: this.match.home_score,
                    away_score: this.match.away_score
                },
                {
                    headers: {
                        Authorization: `Bearer ${this.auth.token}`
                    }
                }
            ).then(() => {
                this.$notify({
                    type: 'success',
                    text: this.saveResultSuccessMsg,
                });
            }).catch((error) => {
                let errMsg = 'Erreur inconnue !';
                if (error.hasOwnProperty('response')
                    && error.response.hasOwnProperty('data')
                    && error.response.data.hasOwnProperty('message')) {
                    errMsg = error.response.data.message;
                }
                this.$notify({
                    type: 'error',
                    text: errMsg
                });
            }).finally(() => {
                this.loading = false;
            });
        }
    },
    template: `
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card" :class="[ match.is_today ? 'border-warning' : 'border-info' ]">
                <div class="card-header text-center bk-match-card-header" 
                    :class="[ match.is_today ? 'bg-warning text-dark' : 'bg-info text-white' ]">
                    {{ match.pretty_kick_off }}
                </div>
                <div class="card-body text-info bk-match-card-content">
                    <div class="container-fluid">
                        <div class="row align-items-center justify-content-center">
                            <div class="col d-flex justify-content-start align-items-center text-uppercase">
                                <div class="w-25">
                                    <img class="img-fluid" :src="flagsUrl + homeTeam.abbreviation" />
                                </div>
                                <div class="w-25 ml-3 bk-team-name">{{ homeTeam.abbreviation }}</div>
                                <div class="w-25 ml-auto">
                                    <input type="number" class="form-control form-control-lg" placeholder="0" step="1"
                                        v-model="homeScore" :readonly="loading == true">
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center justify-content-center">
                            <div class="col d-flex justify-content-start align-items-center text-uppercase">
                                <div class="w-25">
                                    <img class="img-fluid" :src="flagsUrl + awayTeam.abbreviation" />
                                </div>
                                <div class="w-25 ml-3 bk-team-name">{{ awayTeam.abbreviation }}</div>       
                                <div class="w-25 ml-auto">
                                    <input type="number" class="form-control form-control-lg" placeholder="0" step="1"
                                        v-model="awayScore" :readonly="loading == true">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <button class="btn btn-lg btn-block" :class="btnColor" :disabled="loading == true"
                                    @click="saveResult">{{ btnSaveLabel }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
};
