<template>
    <div id="login_form" class="row h-100 align-items-center justify-content-around">
        <div class="col-md-6 col-lg-4">
            <div class="form-group">
                <input type="text" v-model="userLogin" placeholder="Username" :disabled="loading === true"
                       class="form-control form-control-lg" @keyup.enter="formLogIn">
            </div>
            <div class="form-group">
                <input type="password" v-model="userPassword" placeholder="Password" :disabled="loading === true"
                       class="form-control form-control-lg" @keyup.enter="formLogIn">
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <button @click="formLogIn" :disabled="loading === true" class="btn btn-info">
                    Se connecter
                </button>
                <span>OU</span>
                <button @click="formCreateAccount" :disabled="loading === true" class="btn btn-success">
                    Créer un compte
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapState} from 'vuex';

    export default {
        name: "LoginForm",
        data() {
            return {
                userLogin: '',
                userPassword: '',
                loading: false
            };
        },
        computed: mapState(['apiBaseUrl']),
        methods: Object.assign(
            mapActions('authModule', ['logIn', 'createAccount']),
            mapActions(['fetchAll']),
            {
                formLogIn() {
                    this.loading = true;
                    this.logIn({
                        username: this.userLogin,
                        password: this.userPassword
                    }).then(() => {
                        this.fetchAll();
                        this.$router.push({name: 'admin'});
                    }).catch(() => {
                        this.$notify({
                            type: 'error',
                            text: 'Connexion impossible !',
                        });
                    }).finally(() => {
                        this.loading = false;
                    });
                },
                formCreateAccount() {
                    this.loading = true;
                    this.createAccount({
                        username: this.userLogin,
                        password: this.userPassword
                    }).then(() => {
                        this.formLogIn();
                    }).catch(() => {
                        this.$notify({
                            type: 'error',
                            text: 'Inscription impossible !',
                        });
                        this.loading = false;
                    });
                },
            },
        )
    }
</script>

<style lang="sass" scoped>

</style>
