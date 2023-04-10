<template>
    <nav id="navbarBlur" :class="`${!isNavFixed
        ? 'navbar navbar-main navbar-expand-lg px-0 mx-4 border-radius-xl shadow-none'
        : `navbar navbar-main navbar-expand-lg px-0 mx-4 border-radius-xl shadow-none position-sticky ${darkMode ? 'bg-default' : 'bg-white'
        } left-auto top-2 z-index-sticky`
        }`" v-bind="$attrs" data-scroll="true">
        <div class="px-3 py-1 container-fluid">
            <breadcrumbs :current-page="currentRouteName" :current-directory="currentDirectory"/>
            <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none">
                <a href="#" class="p-0 nav-link text-body" @click.prevent="navbarMinimize">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line" :class="
                            isNavFixed && !darkMode ? ' opacity-8 bg-dark' : 'bg-white'
                        "></i>
                        <i class="sidenav-toggler-line" :class="
                            isNavFixed && !darkMode ? ' opacity-8 bg-dark' : 'bg-white'
                        "></i>
                        <i class="sidenav-toggler-line" :class="
                            isNavFixed && !darkMode ? ' opacity-8 bg-dark' : 'bg-white'
                        "></i>
                    </div>
                </a>
            </div>
            <div id="navbar" class="mt-2 collapse navbar-collapse mt-sm-0 me-md-0 me-sm-4">
                <ul class="ms-md-auto navbar-nav justify-content-end">
                    <li class="nav-item dropdown d-flex align-items-center pe-1">
                        <a href="#btnNotification" :class="`p-0 nav-link bg-light avatar-sm rounded-circle d-flex justify-content-center align-items-center ${isNavFixed && !darkMode ? ' opacity-8 text-dark' : 'text-warning'}`" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="cursor-pointer fa fa-bell"></i>
                        </a>
                        <ul id="btnNotification" class="px-2 py-3 dropdown-menu dropdown-menu-end me-sm-n3"
                            aria-labelledby="btnNotification">
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="py-1 d-flex">
                                        <div class="my-auto">
                                            <img src="#" class="avatar avatar-sm me-3" alt="user image" />
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-1 text-sm font-weight-normal">
                                                <span class="font-weight-bold">New message</span> from
                                                Laur
                                            </h6>
                                            <p class="mb-0 text-xs text-secondary">
                                                <i class="fa fa-clock me-1"></i>
                                                13 minutes ago
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="py-1 d-flex">
                                        <div class="my-auto">
                                            <img src="#"
                                                 class="avatar avatar-sm bg-gradient-dark me-3" alt="logo spotify" />
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-1 text-sm font-weight-normal">
                                                <span class="font-weight-bold">New album</span> by
                                                Travis Scott
                                            </h6>
                                            <p class="mb-0 text-xs text-secondary">
                                                <i class="fa fa-clock me-1"></i>
                                                1 day
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="py-1 d-flex">
                                        <div class="my-auto avatar avatar-sm bg-gradient-secondary me-3">
                                            <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <title>credit-card</title>
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                        <g transform="translate(1716.000000, 291.000000)">
                                                            <g transform="translate(453.000000, 454.000000)">
                                                                <path class="color-background"
                                                                      d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                      opacity="0.593633743" />
                                                                <path class="color-background"
                                                                      d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z" />
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-1 text-sm font-weight-normal">
                                                Payment successfully completed
                                            </h6>
                                            <p class="mb-0 text-xs text-secondary">
                                                <i class="fa fa-clock me-1"></i>
                                                2 days
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item d-flex px-1 align-items-center" @click="toggleConfigurator">
                        <img :src="this.user.gravatar" :alt="this.user.name"
                             class="img img-fluid rounded-circle avatar-sm">
                    </li>
                    <li class="nav-item d-xl-none ps-1 d-flex align-items-center">
                        <a id="iconNavbarSidenav" href="#"
                           class="p-0 nav-link text-white bg-light avatar-sm rounded-circle d-flex justify-content-center align-items-center"
                           @click.prevent="navbarMinimize">
                            <div class="sidenav-toggler-inner">
                                <i :class="`sidenav-toggler-line ${isNavFixed && !darkMode ? ' opacity-8 bg-dark' : 'bg-success'
                                    }`"></i>
                                <i :class="`sidenav-toggler-line ${isNavFixed && !darkMode ? ' opacity-8 bg-dark' : 'bg-success'
                                    }`"></i>
                                <i :class="`sidenav-toggler-line ${isNavFixed && !darkMode ? ' opacity-8 bg-dark' : 'bg-success'
                                    }`"></i>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
import breadcrumbs from "@/components/breadcrumbs.vue";
import {mapState, mapMutations, mapActions} from "vuex";
import {MD5} from "md5-js-tools";

export default {
    name: "adminNavbar",
    components: {
        breadcrumbs
    },
    data() {
        return {
            user: null
        };
    },
    computed: {
        ...mapState({
            isNavFixed: state => state.config.isNavFixed,
            darkMode: state => state.config.darkMode,
        }),
        currentRouteName() {
            let dir = this.$route.name.split(".")[1];
            return dir.charAt(0).toUpperCase() + dir.slice(1);
        },
        currentDirectory() {
            let dir = this.$route.name.split(".")[0];
            return dir.charAt(0).toUpperCase() + dir.slice(1);
        }
    },
    created() {
        // this.navbarMinimize();
        this.user = this.getUserInfo();
    },
    beforeUpdate() {
        this.toggleNavigationOnMobile();
    },
    methods: {
        ...mapMutations({
            navbarMinimize: "config/navbarMinimize",
            toggleConfigurator: "config/toggleConfigurator"
        }),
        ...mapActions({
            toggleSidebarColor: "config/toggleSidebarColor",
        }),
        toggleNavigationOnMobile() {
            if (window.innerWidth < 1200) {
                this.navbarMinimize();
            }
        },
        getUserInfo() {
            let user = this.$store.getters['user/userData'].info;
            // get gravatar
            let email = user.email;
            let hash = MD5.generate(email);
            user.gravatar = `https://www.gravatar.com/avatar/${hash}?d=identicon`;
            return user;
        }
    }
};
</script>
