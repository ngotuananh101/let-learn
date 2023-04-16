<template>
    <div class="row mt-3">
        <h2 style="display: flex; justify-content: center; margin-bottom: 50px">Setting</h2>
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-envelope fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">Update your email address</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="new-email">New Email Address</label>
                        <input type="text" id="new-email" name="new-email" class="form-control"
                            placeholder="Enter new email">
                    </div>

                    <div class="form-group">
                        <label for="learn-password">Learn Password</label>
                        <input type="password" id="learn-password" name="learn-password" class="form-control"
                            placeholder="Enter Learn password">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
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
                        <input type="text" id="new-email" name="new-email" class="form-control" placeholder="Enter password"
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
    <div class="row mt-5">
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
                            @change="darkMode" />
                    </div>
                    <label class="form-check-label" for="rememberMe">
                        Dark
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-eye fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">How you appear on Quizlet</h5>
                <div class="form-check" style="margin-left: 10px">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Show your real name on Let Learn
                    </label>
                </div>
                <div class="form-check" style="margin-left: 10px">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck2">
                    <label class="form-check-label" for="defaultCheck2">
                        Show your profile on Let Learn
                    </label>
                    <p>Removing a page from Google takes a variable amount of time depending on how recently Google
                        indexed the page. If you have an urgent need to remove your profile from Google search, please
                        contact us and we can do it for you manually</p>

                </div>
                <div class="form-check">
                    <button type="submit" class="btn btn-behance">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-2 d-flex justify-content-end align-items-center">
            <i class="fa-solid fa-x fs-1"></i>
        </div>
        <div class="col-10">
            <div class="card">
                <h5 style="padding-left: 10px; padding-top: 10px;">Permanently delete Account</h5>
                <div class="card-body">
                    <p>Be careful - this will delete all your data and cannot be undone.</p>
                    <button type="submit" class="btn btn-warning">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
import { activateDarkMode, deactivateDarkMode } from "@/helpers/other/dark-mode";
export default {
    name: "setting",
    data() {
        return {
            password: {
                old_password: null,
                password: null,
                password_confirmation: null,
            },
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
        console.log(this.user.id);
    },
    methods: {
        darkMode() {
            if (this.$store.state.darkMode) {
                activateDarkMode(); // disable dark mode using the helper function
            } else {
                deactivateDarkMode(); // enable dark mode using the helper function
            }
        },
        updatePassword() {
            this.type = 'update';
            let data = {
                old_password: this.password.old_password,
                password: this.password.password,
                password_confirmation: this.password.password_confirmation,
            };
            if (data.password == data.password_confirmation) {
                this.$store.dispatch('home/updatePassword', data);
            }
            else {
                alert('The new password does not match the re-new password');
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
