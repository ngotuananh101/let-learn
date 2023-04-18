import { lessonService } from "../../../services/home/lesson.services";

const state = {
    lessonData: {},
};

export default {
    namespaced: true,
    state: state,
    getters: {
        data: (state) => state.lessonData,
    },
    mutations: {
        request(state) {
            state.lessonData = [];
        },
        success(state, payload) {
            state.lessonData = payload;
        },
        failure(state, payload) {
            state.lessonData = [];
        },
    },
    actions: {
        async importFile({ commit }, formData) {
            commit("request");
            // check file extension from formData
            if (
                formData.get("file").type ===
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            ) {
                try {
                    let response = await lessonService.importExcelFile(
                        formData
                    );
                    commit("success", response);
                } catch (error) {
                    commit("failure", error);
                }
            } else {
                commit("failure", "File type not supported");
            }
        },
        addLesson({ commit }, lesson) {
            commit("request");
            lessonService.addLesson(lesson).then(
                (lesson) => {
                    commit("success", lesson);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        deleteLesson({ commit }, id, roleName) {
            commit("request");
            lessonService.deleteLesson(id, roleName).then(
                (lesson) => {
                    commit("success", lesson);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        getLessonById({ commit }, id, roleName) {
            commit("request");
            lessonService.getLessonById(id,roleName).then(
                (lesson) => {
                    commit("success", lesson);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        updateLesson({ commit }, lesson) {
            commit("request");
            lessonService.updateLesson(lesson).then(
                (lesson) => {
                    commit("success", lesson);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
    },
};
