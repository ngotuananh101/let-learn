import {userService} from '../../services/user.services';

function getUser() {
    let userData = localStorage.getItem('userData');
    if (userData) {
        return JSON.parse(userData);
    } else {
        return null;
    }
}

const userData = getUser();
let state = {
    status: {loggedIn: !!userData},
    userData: userData
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        request(state) {
            state.status = {loggingIn: true};
        },
        loginSuccess(state, data) {
            state.status = {loggedIn: true};
            state.userData = data;
        },
        loginFailure(state, error) {
            state.status = {};
            state.user = null;
        },
        requestSuccess(state, data) {
            state.status = {};
        },
        requestFailure(state, error) {
            state.status = {};
        },
    },
    actions: {
        login({dispatch, commit}, user) {
            commit('request');
            userService.login(user.email, user.password, user.rememberMe)
                .then(
                    data => {
                        commit('loginSuccess', data);
                    },
                    error => {
                        commit('loginFailure', error);
                    }
                );
        },
        register({dispatch, commit}, user) {
            commit('request');
            userService.register(user)
                .then(
                    data => {
                        commit('requestSuccess', data);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        forgotPassword({dispatch, commit}, email) {
            commit('request');
            userService.forgotPassword(email)
                .then(
                    data => {
                        commit('requestSuccess', data);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        resetPassword({dispatch, commit}, data) {
            commit('request');
            userService.resetPassword(data)
                .then(
                    data => {
                        commit('requestSuccess', data);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        logout({dispatch, commit}) {
            userService.logout().then(
                data => {
                    commit('requestSuccess', data);
                },
                error => {
                    commit('requestFailure', error);
                }
            );
        },
        logoutAll({dispatch, commit}) {
            commit('request');
            userService.logout('all').then(
                data => {
                    commit('requestSuccess', data);
                },
                error => {
                    commit('requestFailure', error);
                }
            );
        }
    },
    getters: {
        userData: state => {
            return state.userData;
        }
    }
};
