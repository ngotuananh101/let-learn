import { userService } from '../../services/user.service';
import router from "../../router";
import overlay from "@/helpers/overlay.js";

const user = JSON.parse(localStorage.getItem('user'));
const token = JSON.parse(localStorage.getItem('token'));
const state = user
    ? { status: { loggedIn: true }, user, token }
    : { status: {}, user: null, token: null };

export default {
    namespaced: true,
    state: state,
    mutations: {
        loginRequest(state) {
            state.status = { loggingIn: true };
        },
        loginSuccess(state, user, token) {
            state.status = { loggedIn: true };
            state.user = user;
            state.token = token;
        },
        loginFailure(state) {
            state.status = {};
            state.user = null;
            state.token = null;
        },
        logout(state) {
            state.status = {};
            state.user = null;
            state.token = null;
        },
        registerRequest(state) {
            state.status = { registering: true };
        },
        registerSuccess(state, user) {
            state.status = {};
            state.user = user;
        },
        registerFailure(state, error) {
            state.status = {};
        }
    },
    actions: {
        login({ dispatch, commit }, { email, password, rememberMe }) {
            commit('loginRequest');
            userService.login(email, password, rememberMe)
                .then(
                    user => {
                        commit('loginSuccess', user.data.user, user.data.access_token);
                        dispatch('alert/success', user.message, { root: true });
                        // check if the user is admin
                        if (user.data.user.role.name === 'admin' || user.data.user.role.name === 'super') {
                            setTimeout(() => {
                                router.push({ name: 'admin' })
                            }, 1000);
                        } else {
                            setTimeout(() => {
                                router.push({ name: 'home' })
                            }, 1000);
                        }
                    },
                    error => {
                        commit('loginFailure', error);
                        dispatch('alert/error', error, { root: true });
                    }
                );
        },
        logout({ commit }) {
            userService.logout();
            commit('logout');
        },
        register({ dispatch, commit }, user) {
            commit('registerRequest', user);
            userService.register(user)
                .then(
                    user => {
                        console.log(user);
                        commit('registerSuccess', user);
                        dispatch('alert/success', 'Registration successful', { root: true });
                        setTimeout(() => {
                            router.push({ name: 'home' })
                        }, 1000)
                    },
                    error => {
                        commit('registerFailure', error);
                        dispatch('alert/error', error, { root: true });
                    }
                );
        }
    },
    getters: {
        isAdmin: state => {
            if (state.user) {
                return state.user.role.name === 'admin' || state.user.role.name === 'super';
            }
        },
        getUser: state => {
            return state.user;
        }
    }
};
