import { lessonService } from "../../../services/home/lesson.service";

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
        getLessonInfor({ commit }, id) {
            commit("request");
            lessonService.getLessonInfor(id).then(
                (lesson) => {
                    commit("success", lesson);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        showLessonDetailRelearn({ commit }, id) {
            commit("request");
            lessonService.showLessonDetailRelearn(id).then(
                (relearns) => {
                    commit("success", relearns);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        showLessonDetailNotLearn({ commit }, id) {
            commit("request");
            lessonService.showLessonDetailNotLearn(id).then(
                (notLearns) => {
                    commit("success", notLearns);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        // updateLesson({commit}, lesson) {
        //     commit("request");
        //     lessonService.updateLesson(lesson).then(
        //         lesson => {
        //             commit("success", lesson);
        //         },
        //         error => {
        //             commit("failure", error);
        //         }
        //     )
        // }
    },
};
