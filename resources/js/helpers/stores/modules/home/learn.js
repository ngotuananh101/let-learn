import { learnService } from "../../../services/home/learn.services";
export default {
    namespaced: true,
    state: {
        learnData: {},
    },
    mutations: {
        request(state) {
            state.learnData = {};
        },
        requestSuccess(state, data) {
            state.learnData = data;
        },
        requestFailure(state, error) {
            state.status = {};
        },
    },
    actions: {
        getFlashCard({ commit }, payload) {
            commit("request");
            console.log(payload);
            learnService.loadFlashCard(payload.lesson_id, payload.roleName, payload.userId).then(
                (response) => {
                    commit("requestSuccess", response);
                },
                (error) => {
                    commit("requestFailure", error);
                }
            );
        },
        getNews({ commit }, id) {
            commit("request");
            learnService.loadNew(id).then(
                (response) => {
                    commit("requestSuccess", response);
                },
                (error) => {
                    commit("requestFailure", error);
                }
            );
        },

        getLearn({ commit }, data) {
            commit("request");
            learnService.loadLearn(data).then(
                (response) => {
                    commit("requestSuccess", response);
                },
                (error) => {
                    commit("requestFailure", error);
                }
            );
        },
        relearn({ commit }, data) {
            commit("request");
            learnService.relearn(data).then(
                (response) => {
                    commit("requestSuccess", response);
                },
                (error) => {
                    commit("requestFailure", error);
                }
            );
        },
        getTest({ commit }, id) {
            commit("request");
            learnService.loadTest(id).then(
                (response) => {
                    commit("requestSuccess", response);
                },
                (error) => {
                    commit("requestFailure", error);
                }
            );
        },
        getSelfTest({ commit }, payload) {
            commit("request");
            learnService.loadSelfTest(payload.id, payload.roleName).then(
                (response) => {
                    commit("requestSuccess", response);
                },
                (error) => {
                    commit("requestFailure", error);
                }
            );
        },
        sendTestResult({ commit }, data) {
            commit("request");
            learnService.sendTestResult(data).then(
                (response) => {
                    commit("requestSuccess", response);
                },
                (error) => {
                    commit("requestFailure", error);
                }
            );
        },
        addTest({ commit }, test) {
            commit("request");
            console.log(test);
            learnService.addTest(test).then(
                (test) => {
                    commit("requestSuccess", test);
                },
                (error) => {
                    commit("requestFailure", error);
                }
            );
        },
        async importFile({ commit }, formData) {
            commit("request");
            // check file extension from formData
            if (
                formData.get("file").type ===
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            ) {
                try {
                    let response = await learnService.importExcelFile(formData);
                    commit("requestSuccess", response);
                } catch (error) {
                    commit("requestFailure", error);
                }
            } else {
                commit("requestFailure", "File type not supported");
            }
        },
        updateResult({ commit }, data) {
            console.log(data);
            return learnService.updateResult(data).then(
                (data) => {
                    // console.log("data");
                    return data;
                },
                (error) => {
                    return null;
                }
            );
        },
        updateComment({ commit }, data) {
            console.log(data);
            return learnService.updateComment(data).then(
                (data) => {
                    // console.log("data");
                    return data;
                },
                (error) => {
                    return null;
                }
            );
        },
    },
    getters: {
        userData: (state) => {
            return state.userData;
        },
    },
};
