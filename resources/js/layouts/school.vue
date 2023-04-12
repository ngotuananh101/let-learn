<template>
    <sidenav v-if="showSidenav"/>
    <main className="main-content position-relative max-height-vh-100 h-100">
        <navbar v-if="showNavbar"/>
        <router-view/>
        <!--        <app-footer v-show="showFooter"/>-->
        <configurator
            :class="[showConfig ? 'show' : '', hideConfigButton ? 'd-none' : '']"
        />
    </main>
</template>
<script>
import {mapState} from "vuex";
import sidenav from "../components/sidenavs/school/index.vue";
import navbar from "../components/navbars/school-navbar.vue";
import configurator from "../components/configurator.vue";
export default {
    name: "School Dashboard",
    components: {
        sidenav,
        navbar,
        configurator,
    },
    computed: {
        ...mapState({
            layout: (state) => state.config.layout,
            showNavbar: (state) => state.config.showNavbar,
            showFooter: (state) => state.config.showFooter,
            showSidenav: (state) => state.config.showSidenav,
            showConfig: (state) => state.config.showConfig,
            hideConfigButton: (state) => state.config.hideConfigButton,
        }),
    },
    beforeMount() {
        this.$store.dispatch('school/get', this.$route.params.slug);
    }
};
</script>
