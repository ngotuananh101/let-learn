<template>
    <!-- Navbar -->
    <nav
        class="navbar navbar-expand-lg top-0"
        :class="isBlur ? isBlur : 'shadow-none my-2 navbar-transparent'"
    >
        <div class="container-fluid p-0">
            <div style="height: 3rem">
                <a class="m-0 navbar-brand" href="/">
                    <img
                        src="/logo.png"
                        class="navbar-brand-img h-100"
                        alt="main_logo"
                    />
                    <span class="ms-2 font-weight-bold">Let Learn</span>
                </a>
            </div>
            <div>
                <button
                    class="shadow-none navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navigation"
                    aria-controls="navigation"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="mt-2 navbar-toggler-icon">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </span>
                </button>
            </div>
            <div
                id="navigation"
                class="pt-3 pb-2 collapse navbar-collapse w-100 py-lg-0"
            >
                <div class="d-flex align-items-center ms-md-auto">
                    <div class="input-group">
                        <span class="input-group-text text-body">
                            <i class="fas fa-search" aria-hidden="true"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control"
                            :placeholder="'Type here to search...'"
                            @change="search"
                        />
                    </div>
                </div>
                <ul class="ms-md-4 navbar-nav justify-content-end">
                    <li class="nav-item dropdown d-flex align-items-center pe-1">
                        <a href="#btnNotification" :class="`p-0 nav-link bg-light avatar-sm rounded-circle d-flex justify-content-center align-items-center ${isNavFixed && !darkMode ? ' opacity-8 text-dark' : 'text-warning'}`" data-bs-toggle="dropdown" aria-expanded="false">
                            <img :src="this.user.gravatar" :alt="this.user.name"
                                 class="img img-fluid rounded-circle avatar-sm">
                        </a>
                        <!-- Notification dropdown -->
                        <ul id="btnNotification" class="px-2 py-3 dropdown-menu dropdown-menu-end me-sm-n3" aria-labelledby="btnNotification">
                            <li class="mb-2">
                                <router-link class="dropdown-item border-radius-md" :to="{ name: 'home.setting' }">
                                    <div class="py-1 d-flex align-items-center">
                                        <i class="fa-solid fa-gear me-3"></i>
                                        <span>Setting</span>
                                    </div>
                                </router-link>
                            </li>
                            <li class="mb-2">
                                <router-link class="dropdown-item border-radius-md" :to="{ name: 'home.profile' }">
                                    <div class="py-1 d-flex align-items-center">
                                        <i class="fa-solid fa-user me-3"></i>
                                        <span>Profile</span>
                                    </div>
                                </router-link>
                            </li>
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" :href="'/forum'">
                                    <div class="py-1 d-flex align-items-center">
                                        <i class="fa-solid fa-comments me-3"></i>
                                        <span>Forum</span>
                                    </div>
                                </a>
                            </li>
                            <li class="mb-2 dropdown-item border-radius-md" @click="logout">
                                <div class="py-1 d-flex align-items-center">
                                    <i class="fa-solid fa-right-from-bracket me-3"></i>
                                    <span>Logout</span>
                                </div>
                            </li>
                            <li class="mb-2 dropdown-item border-radius-md" @click="logoutAll">
                                <div class="py-1 d-flex align-items-center">
                                    <i class="fa-solid fa-right-from-bracket me-3"></i>
                                    <span>Logout all device</span>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <!-- End Navbar -->
</template>

<script>
import {MD5} from "md5-js-tools";
import {activateDarkMode, deactivateDarkMode} from "@/helpers/other/dark-mode";

export default {
    name: "Navbar",
    props: {
        btnBackground: {
            type: String,
            default: "",
        },
        isBlur: {
            type: String,
            default: "",
        },
        darkMode: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            user: null,
        };
    },
    beforeMount() {
        this.user = this.getUserInfo();
    },
    methods: {
        getUserInfo() {
            let user = this.$store.getters['user/userData'].info;
            // get gravatar
            let email = user.email;
            let hash = MD5.generate(email);
            user.gravatar = `https://www.gravatar.com/avatar/${hash}?d=identicon`;
            return user;
        },
        async logout() {
            await this.$store.dispatch('user/logout').then(() => {
                location.reload();
            });
        },
        async logoutAll() {
            await this.$store.dispatch('user/logoutAll').then(() => {
                location.reload();
            });
        },
        darkMode() {
            if (this.$store.state.darkMode) {
                this.$store.state.darkMode = false;
                this.sidebar("bg-white");
                deactivateDarkMode();
            } else {
                this.$store.state.darkMode = true;
                this.sidebar("bg-default");
                activateDarkMode();
            }
        },
        search(e) {
            // check is in search page
            if (this.$route.name !== 'home.search') {
                this.$router.push({name: 'home.search', params: {query: e.target.value}});
            }else{
                this.$router.push({name: 'home.search', params: {query: e.target.value}});
                this.$store.dispatch('home/search', e.target.value);
            }
        },
    },
};
</script>
