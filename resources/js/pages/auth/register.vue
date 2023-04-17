<template>
    <div class="card-header bg-transparent">
        <h5 class="text-dark text-center mt-2 mb-3">Register with</h5>
        <div class="btn-wrapper text-center">
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0 me-1">
                <img class="w-30" src="/storage/images/logos/github.svg"/>
                Github
            </a>
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0">
                <img class="w-30" src="/storage/images/logos/google.svg"/>
                Google
            </a>
        </div>
    </div>
    <div class="card-body px-lg-4 pt-0">
        <div class="text-center text-muted mb-4">
            <small>Or register with credentials</small>
        </div>
        <form role="form" class="text-start" ref="form" @submit.prevent="register">
            <div class="form-group mb-3">
                <input id="name" name="name" type="text" class="form-control" placeholder="Enter your name"
                       v-model="user.name" required/>
            </div>
            <div class="form-group mb-3">
                <input id="username" name="username" type="text" class="form-control"
                       placeholder="Enter your username (optional)"
                       v-model="user.username"/>
            </div>
            <div class="form-group mb-3">
                <input id="email" name="email" type="email" class="form-control" placeholder="Enter your email"
                       v-model="user.email" required/>
            </div>
            <div class="form-group mb-3">
                <input id="date_of_birth" name="date_of_birth" type="date" class="form-control"
                       placeholder="Your date of birth"
                       v-model="user.date_of_birth" required/>
            </div>
            <div class="form-group mb-3">
                <input id="password" name="password" type="password" class="form-control"
                       placeholder="Enter your password"
                       v-model="user.password" required/>
            </div>
            <div class="form-group mb-3">
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control"
                       placeholder="Confirm your password"
                       v-model="user.password_confirmation" required/>
            </div>
            <div class="form-check ps-0">
                <input id="agree" class="form-check-input ms-0" type="checkbox" name="agree"
                       v-model="user.agree"/>
                <label class="form-check-label" for="agree">
                    I agree the
                    <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                </label>
            </div>
            <div class="text-center">
                <button class="btn my-4 mb-3 bg-gradient-success w-100">Register</button>
            </div>
        </form>
        <div class="text-start">
            Already have an account?
            <router-link :to="{ name: 'auth.login' }" class="text-dark font-weight-bold">Login</router-link>
        </div>
    </div>
</template>
<script lang="js">
export default {
    name: "Auth Register",
    data() {
        return {
            user: {
                name: "Ngo Tuan Anh",
                username: "ngotuananh2101",
                email: "ngotuananh2101@gmail.com",
                date_of_birth: "2000-01-21",
                password: "password",
                password_confirmation: "password",
                agree: false,
            },
            unsubscribe: null,
        };
    },
    title() {
        return "Login";
    },
    mounted() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'user/request') {
                this.$root.showSnackbar('Registering...', 'info');
            } else if (mutation.type === 'user/requestSuccess') {
                this.$root.showSnackbar('Registered successfully', 'success');
                this.$router.push({name: 'auth.login'});
            } else if (mutation.type === 'user/requestFailure') {
                this.$root.showSnackbar('Register failed', 'danger');
            }
        });
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        register() {
            this.$store.dispatch('user/register', this.user);
        },
    }
};
</script>
