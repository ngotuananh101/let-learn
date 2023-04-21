<template>
    <div class="card-header bg-transparent">
        <h5 class="text-dark text-center mt-2 mb-3">Sign in</h5>
        <div class="btn-wrapper text-center">
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0 me-1">
                <img class="w-30" src="/storage/images/logos/github.svg" />
                Github
            </a>
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0">
                <img class="w-30" src="/storage/images/logos/google.svg" />
                Google
            </a>
        </div>
    </div>
    <div class="card-body px-lg-5 pt-0">
        <div class="text-center text-muted mb-4">
            <small>Or sign in with credentials</small>
        </div>
        <form role="form" class="text-start" ref="form" @submit.prevent="login">
            <div class="form-group mb-3">
                <input id="email" name="email" type="email" class="form-control" placeholder="Enter your email"
                    v-model="email" required />
            </div>
            <div class="form-group mb-3">
                <input id="password" name="password" type="password" class="form-control" placeholder="Enter your password"
                    v-model="password" required />
            </div>
            <div class="form-check form-switch ps-0">
                <input id="rememberMe" class="form-check-input ms-0" type="checkbox" name="rememberMe"
                    v-model="rememberMe" />
                <label class="form-check-label" for="rememberMe">
                    Remember me
                </label>
            </div>
            <div class="text-center">
                <button class="btn my-4 mb-2 bg-gradient-success w-100">Login</button>
                <router-link :to="{ name: 'auth.forgot-password' }" class="text-sm m-0">Forgot your password?</router-link>
            </div>
        </form>
        <div class="mb-2 position-relative text-center">
            <p class="text-sm font-weight-bold mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                or
            </p>
        </div>
        <div class="text-center">
            <router-link :to="{ name: 'auth.register' }" class="btn mt-2 mb-4 bg-gradient-dark w-100">Create new account</router-link>
        </div>
    </div>
</template>
<script lang="js">
export default {
    name: "Auth Login",
    data() {
        return {
            email: "admin@pontas.dev",
            password: "password",
            rememberMe: false,
            unsubscribe: null,
        };
    },
    title() {
        return "Login";
    },
    mounted() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'user/request') {
                this.$root.showSnackbar('Logging in...', 'info');
            } else if (mutation.type === 'user/loginSuccess') {
                this.$root.showSnackbar('Logged in successfully', 'success');
                this.redirectToHomePage();
            } else if (mutation.type === 'user/loginFailure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        login() {
            // validate
            if (!this.email || !this.password) {
                this.$root.showSnackbar('Please enter your email and password', 'warning');
            } else {
                // login
                this.$store.dispatch('user/login', {
                    email: this.email,
                    password: this.password,
                    rememberMe: this.rememberMe,
                }).catch((error) => {
                    this.$root.showSnackbar(error.message, 'danger');
                });
            }
        },
        redirectToHomePage() {
            const user = this.$store.getters['user/userData'];
            const permissions = user?.info?.role?.permissions;
            if (permissions.filter(permission => (permission.name === 'admin.dashboard' || permission.name === 'admin.super')).length > 0) {
                this.$router.push({ name: 'admin' });
            }else if (permissions.filter(permission => (permission.name === 'manager.dashboard')).length > 0) {
                this.$router.push({ name: 'school' });
            } else {
                this.$router.push({ name: 'home' });
            }
        },
    }
};
</script>
