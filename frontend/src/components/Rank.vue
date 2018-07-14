<template>
    <tr :class="[this.auth.userId === this.user.id ? 'text-info font-weight-bold' : '']">
        <td>{{ user.username.length > 20 ? user.username.substring(0,17) + '...' : user.username }}</td>
        <td class="text-right">{{ user.points }}</td>
        <td class="text-right">{{ user.points_alternative }}</td>
        <td class="text-right">{{ user.nbBets }}</td>
        <td class="text-right">{{ user.nbWins }}</td>
        <td class="text-right">{{ user.nbPerfects }}</td>
    </tr>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "Rank",
        computed: mapState(['auth']),
        props: ['user'],
    }
</script>

<style scoped>

</style>
