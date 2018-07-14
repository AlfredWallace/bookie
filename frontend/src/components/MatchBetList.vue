<template>
    <div class="row bk-header-shift">
        <!--<match-bet v-for="match in matches" :match-id="match.id" :key="match.id"></match-bet>-->
    </div>
</template>

<script>
    import MatchBet from './MatchBet';
    import {mapState, mapActions} from 'vuex';

    export default {
        name: "MatchBetList",
        components: {MatchBet},
        computed: mapState('matchModule', ['matches']),
        methods: mapActions(['fetchData']),
        // created: function () {
        //     this.fetchData({url: '/matches', setter: 'matchModule/setMatches'});
        // }
    }
</script>

<style scoped>

</style>
