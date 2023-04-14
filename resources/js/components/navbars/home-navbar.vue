<template>
    <!-- Navbar -->
    <nav
        class="navbar navbar-expand-lg top-0"
        :class="isBlur ? isBlur : 'shadow-none my-2 navbar-transparent'"
    >
        <div class="container-fluid p-0">
            <div style="height: 3rem">
                <router-link class="m-0 navbar-brand" to="/">
                    <img
                        src="/logo.png"
                        class="navbar-brand-img h-100"
                        alt="main_logo"
                    />
                    <span class="ms-2 font-weight-bold">Let Learn</span>
                </router-link>
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
                                    <div class="py-1 d-flex">
                                        <div class="my-auto">
                                            <p class="mb-0 text-xs text-secondary">
                                                <i class="fa-solid fa-gear"></i>
                                            </p>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-1 text-sm font-weight-normal">
                                                <span>Setting</span>
                                            </h6>
                                        </div>
                                    </div>
                                </router-link>
                            </li>
                            <li class="mb-2">
                                <router-link class="dropdown-item border-radius-md" :to="{ name: 'home.profile' }">
                                    <div class="py-1 d-flex">
                                        <div class="my-auto">
                                            <p class="mb-0 text-xs text-secondary">
                                                <i class="fa-solid fa-user"></i>
                                            </p>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-1 text-sm font-weight-normal">
                                                <span>Profile</span>
                                            </h6>
                                        </div>
                                    </div>
                                </router-link>
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
        }
    },
};
</script>
