<template>
    <div>
        <h1>Logout</h1>
        <p>Logging out...</p>
    </div>
</template>
<script lang="js">
import { mapActions} from "vuex";
export default {
    name: "Auth Logout",
    created() {
        this.logout().then(() => {
            this.$router.push({ name: 'login' });
        });
    },
    methods: {
        ...mapActions('account', ['logout']),
    },
};
</script>
