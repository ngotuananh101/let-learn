<template>
    <div class="card-header bg-transparent">
        <h5 class="text-dark text-center mt-2 mb-3">Reset password</h5>
        <p class="mb-0 text-center">Enter new password for your account.</p>
    </div>
    <div class="card-body px-lg-5 pt-0">
        <form role="form" class="text-start" ref="form" @submit.prevent="resetPassword">
            <div class="form-group mb-3">
                <input id="password" name="password" type="password" class="form-control"
                       placeholder="Password"
                       v-model="user.password" required/>
            </div>
            <div class="form-group mb-0">
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control"
                       placeholder="Confirm password"
                       v-model="user.password_confirmation" required/>
            </div>
            <div class="text-center">
                <button class="btn my-4 mb-2 bg-gradient-success w-100">Reset</button>
            </div>
        </form>
    </div>
</template>
<script lang="js">
export default {
    name: "Auth Reset Password",
    data() {
        return {
            user: {
                email: this.$route.query.email,
                token: this.$route.query.token,
                password: "",
                password_confirmation: "",
            },
        };
    },
    title() {
        return "Reset Password";
    },
    mounted() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'user/request') {
                this.$root.showSnackbar('Sending request...', 'info');
            } else if (mutation.type === 'user/requestSuccess') {
                this.$root.showSnackbar('Send request successfully', 'success');
                this.$router.push({name: 'auth.login'});
            } else if (mutation.type === 'user/requestFailure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        resetPassword() {
            this.$store.dispatch('user/resetPassword', this.user);
        },
    }
};
</script>
