import {lessonService} from "../../../services/admin/lesson.service";

const state = {
    data: [],
}

export default {
    namespaced: true,
    state: state,
    getters: {
        data: state => state.data,
    },
    mutations: {
        request(state) {
            state.data = [];
        },
        success(state, payload) {
            state.data = payload;
        },
        failure(state, payload) {
            state.data = [];
        }
    },
    actions: {
        getLessons({commit}) {
            commit("request");
            lessonService.getLessons().then(
                lessons => {
                    commit("success", lessons);
                },
                error => {
                    commit("failure", error);
                }
            )
        },
        async importFile({commit}, formData) {
            commit("request");
            // check file extension from formData
            if (formData.get('file').type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                try {
                    let response = await lessonService.importExcelFile(formData);
                    commit("success", response);
                } catch (error) {
                    commit("failure", error);
                }
            } else{
                commit("failure", "File type not supported");
            }
        },
        add({commit}, lesson) {
            commit("request");
            lessonService.add(lesson).then(
                lesson => {
                    commit("success", lesson);
                },
                error => {
                    commit("failure", error);
                }
            )
        },
        getLessonById({commit}, id) {
            commit("request");
            lessonService.getLessonById(id).then(
                lesson => {
                    commit("success", lesson);
                },
                error => {
                    commit("failure", error);
                }
            )
        },
        updateLesson({commit}, lesson) {
            commit("request");
            lessonService.updateLesson(lesson).then(
                lesson => {
                    commit("success", lesson);
                },
                error => {
                    commit("failure", error);
                }
            )
        }
    }
}
