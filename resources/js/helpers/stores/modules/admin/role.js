import {roleService} from '../../../services/admin/role.service';

const state = {
    roles: null,
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        request(state) {
            state.roles = null;
        },
        success(state, payload) {
            state.roles = payload;
        },
        failure(state) {
            state.roles = null;
        }
    },
    actions: {
        getRoleList({commit}){
            commit('request');
            roleService.getRoleList().then(
                roles => {
                    commit("success", roles);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        getUserByRole({commit}, payload){
            commit('request');
            roleService.getUserByRole(payload).then(
                users => {
                    commit("success", users);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        unassignedRole({commit}, payload){
            commit('request');
            payload.type = 'unassigned';
            roleService.unassignedRole(payload).then(
                users => {
                    commit("success", users);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        assignedRole({commit}, payload){
            commit('request');
            payload.type = 'assigned';
            roleService.unassignedRole(payload).then(
                users => {
                    commit("success", users);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        getRole({commit}, id){
            commit('request');
            roleService.getRole(id).then(
                role => {
                    commit("success", role);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        updateRole({commit}, payload){
            commit('request');
            payload.type = 'role';
            roleService.updateRole(payload).then(
                role => {
                    commit("success", role);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        addRole({commit}, payload){
            commit('request');
            payload.type = 'role';
            roleService.addRole(payload).then(
                role => {
                    commit("success", role);
                },
                error => {
                    commit("failure", error);
                }
            );
        },
        deleteRole({commit}, id){
            commit('request');
            roleService.deleteRole(id).then(
                role => {
                    commit("success", role);
                },
                error => {
                    commit("failure", error);
                }
            );
        }
    }
}
