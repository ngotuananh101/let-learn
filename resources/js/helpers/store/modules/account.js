import { userService } from '../../services/user.service';

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
    },
    actions: {
        login({ dispatch, commit }, { email, password, rememberMe }) {
            commit('loginRequest', { email });
            userService.login(email, password, rememberMe)
                .then(
                    user => {
                        commit('loginSuccess', user);
                        dispatch('alert/success', user.message, { root: true });
                        router.push('/');
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

    }
};
