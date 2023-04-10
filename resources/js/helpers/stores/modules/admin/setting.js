import {settingService} from "../../../services/admin/setting.service";

const state = {
    setting: null
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        request(state) {
            state.setting = null;
        },
        success(state, payload) {
            state.setting = payload;
        },
        failure(state, payload) {
            state.setting = null;
        }
    },
    actions: {
        get({ commit }) {
            commit("request");
            try {
                settingService.get().then(
                    setting => {
                        commit("success", setting.data);
                    },
                    error => {
                        commit("failure", error);
                    }
                );
            } catch (error) {
                commit("failure");
            }
        },
        update({ commit }, payload) {
            commit("request");
            try {
                settingService.update(payload).then(
                    setting => {
                        commit("success", payload);
                    },
                    error => {
                        commit("failure", error);
                    }
                );
            } catch (error) {
                commit("failure");
            }
        }
    },
    getters: {
    }
}
