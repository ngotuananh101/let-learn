import { courseService } from "../../../services/home/course.services";

const state = {
    courseData: [],
};

export default {
    namespaced: true,
    state: state,
    getters: {
        data: (state) => state.courseData,
    },
    mutations: {
        request(state) {
            state.courseData = [];
        },
        success(state, payload) {
            state.courseData = payload;
        },
        failure(state, payload) {
            state.courseData = [];
        },
    },
    actions: {
        getLessonByCourseId({ commit }, course_id) {
            commit("request");
            courseService.getLessonByCourseId(course_id).then(
                (response) => {
                    commit("success", response);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        createCourse({ commit }, course) {
            commit("request");
            courseService.createCourse(course).then(
                (course) => {
                    commit("success", course);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        updateCourse({commit}, course) {
            commit('request');
            courseService.updateCourse(course).then(
                course => {
                    commit('success', course);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        searchLesson({commit}, keyword) {
            commit('request');
            courseService.searchLesson(keyword).then(
                lesson => {
                    commit('success', lesson);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        addLessonToCourse({commit}, data) {
            commit('request');
            data.type = 'add_lesson';
            courseService.addLessonToCourse(data).then(
                lesson => {
                    commit('success', lesson);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        deleteCourse({commit}, id) {
            commit('request');
            courseService.deleteCourse(id).then(
                course => {
                    commit('success', course);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        removeLessonFromCourse({commit}, data) {
            commit('request');
            data.type = 'remove_lesson';
            courseService.removeLessonFromCourse(data).then(
                lesson => {
                    commit('success', lesson);
                },
                error => {
                    commit('failure', error);
                }
            );
        }
    },
};
