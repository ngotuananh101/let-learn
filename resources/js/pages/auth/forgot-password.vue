<template>
    <div class="card-header bg-transparent">
        <h5 class="text-dark text-center mt-2 mb-3">Forgot password</h5>
        <p class="mb-0 text-center">
            Please enter your email address and we will send you a link to reset your password.<br>If you do not receive an email, please check your <strong>spam</strong> folder.
        </p>
    </div>
    <div class="card-body px-lg-5 pt-0">
        <form role="form" class="text-start" ref="form" @submit.prevent="forgotPassword">
            <div class="form-group mb-3">
                <input id="email" name="email" type="email" class="form-control" placeholder="Enter your email"
                       v-model="email" required />
            </div>
            <div class="text-center">
                <button class="btn my-1 mb-2 bg-gradient-success w-100">Send</button>
                <router-link :to="{ name: 'auth.login' }" class="text-sm m-0">Login to your account</router-link>
            </div>
        </form>
    </div>
</template>
<script lang="js">
export default {
    name: "Auth Forgot Password",
    data() {
        return {
            email: "ngotuananh2101@gmail.com",
        };
    },
    title() {
        return "Forgot Password";
    },
    mounted() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'user/request') {
                this.$root.showSnackbar('Sending request...', 'info');
            } else if (mutation.type === 'user/requestSuccess') {
                this.$root.showSnackbar('Send request successfully', 'success');
            } else if (mutation.type === 'user/requestFailure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        forgotPassword() {
            this.$store.dispatch('user/forgotPassword', this.email);
        },
    }
};
</script>
