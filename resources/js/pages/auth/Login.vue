<template>
    <div class="card-header bg-transparent">
        <h5 class="text-dark text-center mt-2 mb-3">Sign in</h5>
        <div class="btn-wrapper text-center">
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0 me-1">
                <img class="w-30" src="https://s3-hcm-r1.longvan.net/19403879-letlearn/images/github.svg" />
                Github
            </a>
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0">
                <img class="w-30" src="https://s3-hcm-r1.longvan.net/19403879-letlearn/images/google.svg" />
                Google
            </a>
        </div>
    </div>
    <div class="card-body px-lg-5 pt-0">
        <div class="text-center text-muted mb-4">
            <small>Or sign in with credentials</small>
        </div>
        <form role="form" class="text-start">
            <div class="mb-3">
                <argon-input id="email" type="email" placeholder="Email" aria-label="Email" />
            </div>
            <div class="mb-3">
                <argon-input id="password" type="password" placeholder="Password" aria-label="Password" />
            </div>
            <argon-switch id="rememberMe" name="rememberMe">
                Remember me
            </argon-switch>

            <div class="text-center">
                <argon-button color="success" variant="gradient" full-width class="my-4 mb-2" >Sign in</argon-button>
            </div>
            <div class="mb-2 position-relative text-center">
                <p class="text-sm font-weight-bold mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                    or
                </p>
            </div>
            <div class="text-center">
                <router-link :to="{ name: 'auth.register' }" class="text-secondary">
                    <argon-button color="dark" variant="gradient" full-width class="mt-2 mb-4">Sign up</argon-button>
                </router-link>
            </div>
        </form>
    </div>
</template>
<script lang="js">
import { mapMutations } from "vuex";
import ArgonInput from "@/components/ArgonInput.vue";
import ArgonSwitch from "@/components/ArgonSwitch.vue";
import ArgonButton from "@/components/ArgonButton.vue";
export default {
    name: "Auth",
    components: {
        ArgonInput,
        ArgonSwitch,
        ArgonButton,
    },
    created() {
        this.$store.state.hideConfigButton = true;
        this.toggleDefaultLayout();
    },
    beforeUnmount() {
        this.$store.state.hideConfigButton = false;
        this.toggleDefaultLayout();
    },
    methods: {
        ...mapMutations(["toggleDefaultLayout"]),
    },
};
</script>
