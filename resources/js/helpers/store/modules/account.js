import {userService} from '../../services/user.service';
import router from "../../router";
import overlay from "@/helpers/overlay.js";

const user = JSON.parse(localStorage.getItem('user'));
const token = localStorage.getItem('token');
const state = user
    ? {status: {loggedIn: true}, user, token}
    : {status: {}, user: null, token: null};

export default {
    namespaced: true,
    state: state,
    mutations: {
        loginRequest(state) {
            state.status = {loggingIn: true};
        },
        loginSuccess(state, user, token) {
            state.status = {loggedIn: true};
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
            state.status = {registering: true};
        },
        registerSuccess(state, user) {
            state.status = {};
            state.user = user;
        },
        registerFailure(state, error) {
            state.status = {};
        },
    },
    actions: {
        login({dispatch, commit}, {email, password, rememberMe}) {
            commit('loginRequest');
            userService.login(email, password, rememberMe)
                .then(
                    user => {
                        commit('loginSuccess', user.data.user, user.data.access_token);
                        dispatch('alert/success', user.message, {root: true});
                        // check if the user is admin
                        if (user.data.user.role.name === 'admin' || user.data.user.role.name === 'super') {
                            setTimeout(() => {
                                router.push({name: 'admin'})
                            }, 1000);
                        } else {
                            setTimeout(() => {
                                router.push({name: 'home'})
                            }, 1000);
                        }
                    },
                    error => {
                        commit('loginFailure', error);
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        logout({commit}) {
            userService.logout();
            commit('logout');
        },
        register({dispatch, commit}, user) {
            commit('registerRequest', user);
            userService.register(user)
                .then(
                    user => {
                        console.log(user);
                        commit('registerSuccess', user);
                        dispatch('alert/success', 'Registration successful', {root: true});
                        setTimeout(() => {
                            router.push({name: 'home'})
                        }, 1000)
                    },
                    error => {
                        commit('registerFailure', error);
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        forgotPassword({dispatch, commit}, email) {
            userService.forgotPassword(email)
                .then(
                    user => {
                        dispatch('alert/success', user.message, {root: true});
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        resetPassword({dispatch, commit}, {email, password, password_confirmation, token}) {
            userService.resetPassword(email, password, password_confirmation, token)
                .then(
                    user => {
                        dispatch('alert/success', user.message, {root: true});
                        setTimeout(() => {
                            router.push({name: 'auth.login'})
                        }, 1000)
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        verifyEmail({dispatch, commit}, {id, hash, expires, signature}) {
            userService.verifyEmail(id, hash, expires, signature)
                .then(
                    user => {
                        dispatch('alert/success', user.message, {root: true});
                        setTimeout(() => {
                            router.push({name: 'auth.login'})
                        }, 1000)
                    },
                    error => {
                        dispatch('alert/error', error, {root: true}).then(() => {
                            dispatch('alert/error', "You must login to verify email", {root: true}).then(() => {
                                setTimeout(() => {
                                    router.push({name: 'auth.login'})
                                }, 1000)
                            });
                        });
                    }
                );
        },
        resendVerificationEmail({dispatch, commit}) {
            userService.resendVerificationEmail()
                .then(
                    user => {
                        dispatch('alert/success', user.message, {root: true});
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
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
        },
        isEmailVerified: state => {
            return state.user.email_verified_at;
        }
    }
};
