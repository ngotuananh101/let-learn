<template>
    <div class="fixed-plugin">
        <div class="shadow-lg card">
            <div class="pt-3 pb-0 bg-transparent card-header">
                <div class="float-start">
                    <h5 class="mt-3 mb-0">Hello, {{ this.user.name }} !</h5>
                </div>
                <div class="mt-4 float-end" @click="toggleConfigurator">
                    <button class="p-0 btn btn-link text-dark fixed-plugin-close-button">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <!-- End Toggle Button -->
            </div>
            <hr class="my-1 horizontal dark"/>
            <div class="pt-0 card-body pt-sm-3 d-flex flex-column justify-content-between">
                <!-- Sidebar Backgrounds -->
                <div>
                    <h6 class="mb-1">Your basic info</h6>
                    <p class="text-sm mb-1">Name: {{ this.user.name }}</p>
                    <p class="text-sm mb-1">Username: {{ this.user.username }}</p>
                    <p class="text-sm mb-1">Email: {{ this.user.email }}</p>
                    <p class="text-sm mb-1">Date of birth: {{ this.user.date_of_birth }}</p>
                    <p class="text-sm">Role: {{ this.user.role.name }}</p>
                    <h6 class="mb-0">Sidebar Colors</h6>
                    <div class="switch-trigger background-color">
                        <div
                            class="my-2 badge-colors"
                            :class="isRTL ? 'text-end' : ' text-start'"
                        >
            <span
                class="badge filter bg-gradient-primary"
                data-color="primary"
                @click="sidebarColor('primary')"
            ></span>
                            <span
                                class="badge filter bg-gradient-dark"
                                data-color="dark"
                                @click="sidebarColor('dark')"
                            ></span>
                            <span
                                class="badge filter bg-gradient-info"
                                data-color="info"
                                @click="sidebarColor('info')"
                            ></span>
                            <span
                                class="badge filter bg-gradient-success"
                                data-color="success"
                                @click="sidebarColor('success')"
                            ></span>
                            <span
                                class="badge filter bg-gradient-warning"
                                data-color="warning"
                                @click="sidebarColor('warning')"
                            ></span>
                            <span
                                class="badge filter bg-gradient-danger"
                                data-color="danger"
                                @click="sidebarColor('danger')"
                            ></span>
                        </div>
                    </div>
                    <div class="mt-3 d-flex">
                        <h6 class="mb-0">Navbar Fixed</h6>
                        <div class="form-check form-switch ps-0 ms-auto my-auto">
                            <input
                                id="navbarFixed"
                                class="mt-1 form-check-input"
                                :class="isRTL ? 'float-end  me-auto' : ' ms-auto'"
                                type="checkbox"
                                :checked="isNavFixed"
                                @click="navbarFixed"
                            />
                        </div>
                    </div>
                    <hr class="my-3 horizontal dark"/>
                    <div class="mt-2 d-flex">
                        <h6 class="mb-0">Sidenav Mini</h6>

                        <div class="form-check form-switch ps-0 ms-auto my-auto">
                            <input
                                id="navbarMinimize"
                                class="mt-1 form-check-input"
                                :class="isRTL ? 'float-end  me-auto' : ' ms-auto'"
                                type="checkbox"
                                @click="navbarMinimize"
                            />
                        </div>
                    </div>
                    <hr class="horizontal dark my-3"/>
                    <div class="mt-2 d-flex">
                        <h6 class="mb-0">Light / Dark</h6>
                        <div class="form-check form-switch ps-0 ms-auto my-auto">
                            <input
                                class="form-check-input mt-1 ms-auto"
                                type="checkbox"
                                :checked="$store.state.darkMode"
                                @click="darkMode"
                            />
                        </div>
                    </div>
                </div>
                <div class="text-center w-100">
                    <hr class="horizontal dark"/>
                    <div class="btn btn-outline-warning w-100 mb-1" @click="this.logout">Logout</div>
                    <div class="btn btn-outline-danger w-100 mb-0" @click="this.logoutAll">Logout all device</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {mapState, mapMutations, mapActions} from "vuex";
import {activateDarkMode, deactivateDarkMode} from "@/helpers/other/dark-mode";
import {MD5} from "md5-js-tools";

export default {
    name: "Configurator",
    computed: {
        ...mapState({
            isNavFixed: state => state.config.navbarFixed,
            sidebarType: state => state.config.sidebarType,
            isRTL: state => state.config.isRTL
        })
    },
    data() {
        return {
            user: null,
            unsubscribe: null,
        };
    },
    beforeMount() {
        this.user = this.getUserInfo();
    },
    mounted() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'user/request') {
                this.$root.showSnackbar('Logging out...', 'info');
            } else if (mutation.type === 'user/requestSuccess') {
                this.$root.showSnackbar('Logged out successfully', 'success');
                location.reload();
            } else if (mutation.type === 'user/requestFailure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        ...mapMutations({
            navbarMinimize: "config/navbarMinimize",
            navbarFixed: "config/navbarFixed",
            setSidebarType: "config/setSidebarType",
            toggleConfigurator: "config/toggleConfigurator"
        }),
        ...mapActions({
            toggleSidebarColor: "config/toggleSidebarColor"
        }),
        sidebarColor(color = "success") {
            document.querySelector("#sidenav-main").setAttribute("data-color", color);
        },
        sidebar(type) {
            this.setSidebarType(type);
        },
        getUserInfo() {
            let user = this.$store.getters['user/userData'].info;
            // get gravatar
            let email = user.email;
            let hash = MD5.generate(email);
            user.gravatar = `https://www.gravatar.com/avatar/${hash}?d=identicon`;
            return user;
        },
        logout() {
            this.$store.dispatch('user/logout');
        },
        logoutAll() {
            this.$store.dispatch('user/logoutAll');
        }
    }
};
</script>
