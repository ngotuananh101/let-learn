<template>
    <div class="card-header text-center pt-4 pb-1">
        <h4 class="font-weight-bolder mb-1">Loging Out</h4>
        <p class="mb-0">
            You are being logged out. Please wait...
        </p>
    </div>
</template>
<script lang="js">
import { mapActions} from "vuex";
export default {
    name: "Auth Logout",
    created() {
        this.logout().then(() => {
            location.href = '/auth/login';
        });
    },
    methods: {
        ...mapActions('account', ['logout']),
    },
};
</script>
