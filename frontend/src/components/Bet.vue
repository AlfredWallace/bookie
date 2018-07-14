<template>
    <tr :class="classObject">
        <td>{{ bet.username.length > 10 ? bet.username.substring(0,7) + '...' : bet.username }}</td>
        <td class="text-center">{{ bet.home_score }} - {{ bet.away_score }}</td>
        <td class="text-right">{{ bet.points }}</td>
        <td class="text-right">{{ bet.points_alternative }}</td>
    </tr>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "Bet",
        data: function () {
            return {
                classObject: null,
            };
        },
        props: ['bet', 'match'],
        computed: mapState(['auth']),
        created() {
            this.classObject = {
                'text-success': this.match.is_over && this.bet.points > 0,
                'text-danger': this.match.is_over && this.bet.points === 0,
                'bg-light font-weight-bold': this.auth.userId === this.bet.userId
            };
        },
    }
</script>

<style scoped>

</style>
