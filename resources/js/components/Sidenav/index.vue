<template>
    <div v-show="app.layout === 'default'" class="min-height-300 position-absolute w-100"
        :class="`${app.darkMode ? 'bg-trasnparent' : 'bg-success'}`" />

    <aside id="sidenav-main" class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 ms-4"
        :class="` ${app.sidebarType} fixed-start ms-3 ${app.layout === 'landing' ? 'bg-transparent shadow-none' : ''}`">
        <div class="sidenav-header">
            <i id="iconSidenav"
                class="top-0 p-3 cursor-pointer fas fa-times text-secondary opacity-5 position-absolute end-0 d-none d-xl-none"
                aria-hidden="true"></i>
            <router-link class="m-0 navbar-brand" to="/">
                <img :src="
    app.layout === 'landing' || app.darkMode || app.sidebarType === 'bg-default'
        ? logoWhite
        : logo
                " class="navbar-brand-img h-100" alt="main_logo" />
                <span class="ms-2 font-weight-bold">Let Learn Team</span>
            </router-link>
        </div>
        <hr class="mt-0 horizontal dark" />
        <sidenav-list />
    </aside>
</template>
<script>
import { mapState } from "vuex";
import SidenavList from "./SidenavList.vue";
export default {
    name: "Index",
    components: {
        SidenavList,
    },
    data() {
        return {
            logo: 'https://avatars.githubusercontent.com/u/6128107?s=200&v=4',
            logoWhite: 'https://demos.creative-tim.com/argon-dashboard-pro/assets/img/brand/argon-react-white.png',
            image: 'https://demos.creative-tim.com/argon-dashboard-pro/assets/img/theme/img-1-1000x600.jpg',
        };
    },
    computed: {
        ...mapState(['app'])
    },
    beforeUnmount() {
        this.$store.state.app.image;
    }
};
</script>
