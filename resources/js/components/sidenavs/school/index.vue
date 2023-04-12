<template>
    <div
        class="min-height-300 position-absolute w-100"
        :class="`${darkMode ? 'bg-trasnparent' : 'bg-success'}`"
    />
    <aside
        id="sidenav-main"
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl overflow-hidden my-3 ms-4"
        :class="` ${sidebarType} ${
      isRTL ? 'fixed-end me-3 rotate-caret' : 'fixed-start ms-3'
    }`"
    >
        <div class="sidenav-header">
            <i
                id="iconSidenav"
                class="top-0 p-3 cursor-pointer fas fa-times text-secondary opacity-5 position-absolute end-0 d-none d-xl-none"
                aria-hidden="true"
            ></i>
            <router-link class="m-0 navbar-brand" to="/school">
                <img
                    :src="school.logo"
                    class="navbar-brand-img h-100"
                    alt="school logo"
                />
                <span class="ms-2 font-weight-bold">{{ school.name }}</span>
            </router-link>
        </div>
        <hr class="mt-0 horizontal dark"/>
        <sidenav-list/>
    </aside>
</template>
<script>
import SidenavList from "./items.vue";
import {mapState} from "vuex";
import store from "../../../helpers/stores";
export default {
    name: "School Sidenav",
    components: {
        SidenavList
    },
    data() {
        return {
            school: {
                name: "School manager",
                logo: "logo.png",
            },
        };
    },
    beforeMount() {
        let school = store.getters['user/userData'].info.school;
        if (school) {
            this.school = {
                name: school.name,
                logo: school.logo,
            };
        }else{
            this.school = {
                name: "School",
                logo: "logo.png",
            };
        }
    },
    computed: {
        ...mapState({
            layout: (state) => state.config.layout,
            sidebarType: (state) => state.config.sidebarType,
            isRTL: (state) => state.config.isRTL,
            darkMode: (state) => state.config.darkMode
        }),
    },
};
</script>
