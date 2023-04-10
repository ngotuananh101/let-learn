import {userService} from "../../../services/admin/user.service";

const state = {
    users: null,
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        request(state) {
            state.notification = null;
        },
        success(state, payload) {
            state.notification = payload;
        },
        failure(state) {
            state.notification = null;
        }
    },
    actions: {
        getUserList({commit}){
            commit('request');
            userService.getUserList().then(
                users => {
                    commit("success", users);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        addUser({commit}, user){
            commit('request');
            // convert datetime-local to UCT
            user.email_verified_at = user.email_verified_at ? new Date(user.email_verified_at).toUTCString() : "";
            userService.addUser(user).then(
                user => {
                    commit("success", user);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        updateUser({commit}, user){
            commit('request');
            user.email_verified_at = user.email_verified_at ? new Date(user.email_verified_at).toUTCString() : "";
            userService.updateUser(user).then(
                user => {
                    commit("success", user);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        deleteUser({commit}, id){
            commit('request');
            userService.deleteUser(id).then(
                user => {
                    commit("success", user);
                },
                error => {
                    commit("failure", error);
                }
            );
        }
    }
}
