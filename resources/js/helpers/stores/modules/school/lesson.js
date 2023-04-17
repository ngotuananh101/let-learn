import {lessonService} from '../../../services/school/lesson.service'

const state = {
    lessons: [],
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        success(state, lessons) {
            state.lessons = lessons;
        },
        failure(state) {
            state.dashboard = {};
        },
        request(state) {
            state.dashboard = {};
        }
    },
    actions: {
        index({commit}, slug) {
            commit('request');
            lessonService.index(slug).then(
                lessons => {
                    commit('success', lessons);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        getLessonById({commit}, id) {
            commit('request');
            lessonService.getLessonById(id).then(
                lesson => {
                    commit('success', lesson);
                },
                error => {
                    commit('failure', error);
                }
            );
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
        updateLesson({commit}, lesson) {
            commit('request');
            lessonService.updateLesson(lesson).then(
                lesson => {
                    commit('success', lesson);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        add({commit}, lesson) {
            commit('request');
            lessonService.add(lesson).then(
                lesson => {
                    commit('success', lesson);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        deleteLesson({commit}, id) {
            commit('request');
            lessonService.deleteLesson(id).then(
                lesson => {
                    commit('success', lesson);
                },
                error => {
                    commit('failure', error);
                }
            );
        }
    }
}
