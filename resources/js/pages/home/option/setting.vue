<template>
    <div class="row mt-3">
        <h2 style="display: flex; justify-content: center; margin-bottom: 50px">Setting</h2>
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-envelope fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">Change information</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="new-name" name="new-name" class="form-control" placeholder="Enter name" v-model="user.name">
                    </div>

                    <div class="form-group">
                        <label for="date-birth">Date of Birth:</label>
                        <input type="date" id="date-birth" name="date-birth" class="form-control" placeholder="Enter date of birth" v-model="user.date_of_birth">
                    </div>

                    <button type="submit" class="btn btn-primary" @click="changeInfo()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-lock fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">Change PassWord</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="new-email">Current PassWord</label>
                        <input type="text" id="new-email" name="new-email" class="form-control"
                               placeholder="Enter password"
                               v-model="this.password.old_password">
                    </div>

                    <div class="form-group">
                        <label for="learn-password">New Password</label>
                        <input type="password" id="learn-password" name="learn-password" class="form-control"
                               placeholder="Enter new password" v-model="this.password.password">
                    </div>
                    <div class="form-group">
                        <label for="learn-password">Re-New Password</label>
                        <input type="password" id="learn-password" name="learn-password" class="form-control"
                               placeholder="Enter re-new password" v-model="this.password.password_confirmation">
                    </div>

                    <button type="submit" class="btn btn-primary" @click="updatePassword">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5 pb-5">
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-moon-cloud fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">Night Mode</h5>
                <div class="mt-1 d-flex">
                    <label class="form-check-label mx-2" for="rememberMe">
                        Light
                    </label>
                    <div class="form-check form-switch mx-2">
                        <input class="form-check-input" type="checkbox" v-model="$store.state.darkMode"
                               @change="darkMode"/>
                    </div>
                    <label class="form-check-label" for="rememberMe">
                        Dark
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import {activateDarkMode, deactivateDarkMode} from "@/helpers/other/dark-mode";

export default {
    name: "setting",
    data() {
        return {
            password: {
                old_password: null,
                password: null,
                password_confirmation: null,
            },
            id: this.$route.params.id,
            user: null,
            type: null,
            success: false,
        };
    },
    computed: {
        ...mapState({
            isNavFixed: state => state.config.navbarFixed,
            sidebarType: state => state.config.sidebarType,
            isRTL: state => state.config.isRTL
        })
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
    },
    methods: {
        darkMode() {
            if (this.$store.state.darkMode) {
                activateDarkMode(); // disable dark mode using the helper function
            } else {
                deactivateDarkMode(); // enable dark mode using the helper function
            }
        },
        changeInfo() {
            this.type = 'update';
            let data = {
                role: this.user.role.name,
                name: this.user.name,
                date_of_birth: this.user.date_of_birth,
                id: this.user.id,
            };
            if(data.name === "" || data.date_of_birth === ""){
                alert('Please fill in all the fields');
            }
            this.$store.dispatch('home/changeInfor', data);
            // console.log(data);
            // //logout
            // this.logout();
            // location.reload();
        },
        logout() {
            this.$store.dispatch('user/logout');
        },
        updatePassword() {
            this.type = 'update';
            let data = {
                old_password: this.password.old_password,
                password: this.password.password,
                password_confirmation: this.password.password_confirmation,
                id: this.user.id,
                roleName: this.user.role.name,
            };
            if (data.password === "" || data.password_confirmation === "" || data.old_password === "") {
                alert('Please fill in all the fields');
            } else if (data.old_password === data.password || data.old_password === data.password_confirmation) {
                alert('The new password cannot be the same as the old password');
            } else if (data.password !== data.password_confirmation) {
                alert('The new password does not match the re-new password');
            } else if (data.password === data.password_confirmation && data.old_password !== data.password && data.old_password !== data.password_confirmation) {
                this.$store.dispatch('home/updatePassword', data);
                console.log(data);
                //push to login page
                this.$router.push('/auth/login');
            }
        }
    },
    mutations: {
        toggleDarkMode(state, value) {
            state.darkMode = value;
        },
    },

};

</script>

<style scoped>
.card-body .form-group {
    margin-bottom: 1rem;
}

.card-body input[type="text"],
.card-body input[type="password"] {
    width: 100%;
}
</style>
