import {adminRoleService} from '../../../services/admin/role.service';
import overlay from '../../../overlay';

const state = {
    status: {},
    roles: []
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, roles) {
            state.status = {index: true};
            state.roles = roles;
        },
        indexFailure(state) {
            state.status = {};
            state.roles = [];
        }
    },
    actions: {
        index({commit}) {
            overlay();
            commit('indexRequest');
            return adminRoleService.index()
                .then(
                    roles => {
                        commit('indexSuccess', roles);
                        overlay();
                        return Promise.resolve(roles);
                    },
                    error => {
                        commit('indexFailure', error);
                        overlay();
                        return [];
                    }
                );
        }
    }
}
