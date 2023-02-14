import { userService } from '../../services/user.service';
import router from "../../router";
import overlay from "@/helpers/overlay.js";

const user = JSON.parse(localStorage.getItem('user'));
const state = user
    ? { status: { loggedIn: true }, user }
    : { status: {}, user: null };

export default {
    namespaced: true,
    state: state,
    mutations: {
        loginRequest(state, user) {
            state.status = { loggingIn: true };
            state.user = user;
        },
        loginSuccess(state, user) {
            state.status = { loggedIn: true };
            state.user = user;
        },
        loginFailure(state) {
            state.status = {};
            state.user = null;
        },
        logout(state) {
            state.status = {};
            state.user = null;
        },
        registerRequest(state, user) {
            state.status = { registering: true };
        },
        registerSuccess(state, user) {
            state.status = {};
        },
        registerFailure(state, error) {
            state.status = {};
        }
    },
    actions: {
        login({ dispatch, commit }, { email, password, rememberMe }) {
            commit('loginRequest', { email });
            overlay();
            userService.login(email, password, rememberMe)
                .then(overlay())
                .then(
                    user => {
                        commit('loginSuccess', user);
                        dispatch('alert/success', user.message, { root: true });
                        router.push({ name: 'home' })
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
            overlay();
            userService.register(user)
                .then(overlay())
                .then(
                    user => {
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
    }
};
