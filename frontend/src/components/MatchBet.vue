<script>
    import editableMatchMixin from '../mixins/EditableMatch';
    import {mapGetters, mapMutations} from 'vuex';

    export default {
        name: "MatchBet",
        mixins: [editableMatchMixin],
        computed: Object.assign(
            mapGetters('betModule', ['getBetById', 'getBetByMatchAndUser']),
            {
                bet() {
                    return this.getBetByMatchAndUser(this.matchId, this.auth.userId);
                },
                //todo link this o bet, not match
                homeScore: {
                    get() {
                        return this.match.home_score;
                    },
                    set(score) {
                        this.setHomeScore({id: this.matchId, score: score});
                    },
                },
                awayScore: {
                    get() {
                        return this.match.away_score;
                    },
                    set(score) {
                        this.setAwayScore({id: this.matchId, score: score});
                    },
                },
            }
        ),
        data() {
            return {
                btnSaveLabel: 'Enregistrer mon pari',
                btnColor: 'btn-success',
                saveResultPath: '/bets/group-stage',
                saveResultSuccessMsg: 'Pari sauvegardé !',
            };
        },
        methods: Object.assign(
            mapMutations({
                setHomeScore: 'betModule/setHomeScore',
                setAwayScore: 'betModule/setAwayScore',
            }),
            {
                saveResult() {
                    // this.loading = true;
                    // this.axios.post(this.auth.apiBaseUrl + '/bets/group-stage',
                    //     {
                    //         user: this.auth.userId,
                    //         match: this.match.id,
                    //         home_score: this.homeScore === '' ? 0 : this.homeScore,
                    //         away_score: this.awayScore === '' ? 0 : this.awayScore
                    //     },
                    //     {
                    //         headers: {
                    //             Authorization: `Bearer ${this.auth.token}`
                    //         }
                    //     }
                    // ).then(() => {
                    //     this.loading = false;
                    //     this.$notify({
                    //         type: 'success',
                    //         text: 'Pari sauvegardé !'
                    //     });
                    // }).catch((error) => {
                    //     this.loading = false;
                    //     let errMsg = 'Erreur inconnue !';
                    //     if (error.hasOwnProperty('response')
                    //         && error.response.hasOwnProperty('data')
                    //         && error.response.data.hasOwnProperty('message')) {
                    //         errMsg = error.response.data.message;
                    //     }
                    //     this.$notify({
                    //         type: 'error',
                    //         text: errMsg
                    //     });
                    // });
                }
            }),
    }
</script>

<style scoped>

</style>
