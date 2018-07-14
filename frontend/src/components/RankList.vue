<template>
    <div class="bk-header-shift">
        <div class="row">
            <div class="col">
                <table class="table table-bordered table-striped table-sm bk-ranks">
                    <thead>
                    <tr>
                        <th>Joueur</th>
                        <th class="text-right">
                            <span class="d-none d-sm-inline">Points (1)</span>
                            <font-awesome-icon icon="trophy"></font-awesome-icon>
                        </th>
                        <th class="text-right">
                            <span class="d-none d-sm-inline">Points (2)</span>
                            <font-awesome-icon icon="balance-scale"></font-awesome-icon>
                        </th>
                        <th class="text-right">
                            <span class="d-none d-sm-inline">Paris</span>
                            <font-awesome-icon icon="receipt"></font-awesome-icon>
                        </th>
                        <th class="text-right">
                            <span class="d-none d-sm-inline">Résultat</span>
                            <font-awesome-icon icon="check"></font-awesome-icon>
                        </th>
                        <th class="text-right">
                            <span class="d-none d-sm-inline">Dans le mille</span>
                            <font-awesome-icon icon="crosshairs"></font-awesome-icon>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <rank v-for="user in users" :user="user" :key="user.id"></rank>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="jumbotron">
                        <span class="h3">
                            <font-awesome-icon icon="trophy">
                            </font-awesome-icon> Calcul des points<br>(première méthode)
                        </span>
                    <hr class="display-4">
                    <div class="lead">
                        <span class="h5">Résultat</span>
                        <p>
                            Résultat correct : 5 points
                        </p>
                        <span class="h5">Écart au score</span>
                        <p>
                            Score exact : +5<br>
                            1 but d'écart : +4<br>
                            2 buts d'écart : +3<br>
                            3 buts d'écart : +2<br>
                            4 buts d'écart : +1<br>
                            plus de 5 buts d'écart : +0
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="jumbotron">
                        <span class="h3">
                            <font-awesome-icon icon="balance-scale">
                            </font-awesome-icon> Calcul des points<br>(seconde méthode)
                        </span>
                    <hr class="display-4">
                    <div class="lead">
                        <span class="h5">Résultat</span>
                        <p>
                            Résultat correct : 10 points
                        </p>
                        <span class="h5">Écart au score</span>
                        <p>
                            Score exact : +10<br>
                            1 but d'écart : +7<br>
                            2 buts d'écart : +5<br>
                            3 buts d'écart : +3<br>
                            4 buts d'écart : +2<br>
                            5 buts d'écart : +1<br>
                            plus de 6 buts d'écart : +0
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Rank from './Rank';
    import {mapActions, mapState} from 'vuex';

    export default {
        name: "RankList",
        components: {Rank},
        computed: mapState('rankModule', ['users']),
        methods: mapActions({fetchUsers: 'rankModule/fetchUsers'}),
        created() {
            this.fetchUsers();
        },
    }
</script>

<style scoped>

</style>
