import {courseService} from '../../../services/admin/course.service'

const state = {
    courses: [],
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        request(state) {
            state.courses = [];
        },
        success(state, payload) {
            state.courses = payload.courses;
        },
        failure(state, payload) {
            state.courses = [];
        }
    },
    actions: {
        getAll({commit}) {
            commit('request');
            courseService.getAll().then(
                courses => {
                    commit('success', {courses: courses});
                },
                error => {
                    commit('failure', {error: error});
                }
            );
        },
        add({commit}, course) {
            commit('request');
            courseService.add(course).then(
                course => {
                    commit('success', course);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        getCourseById({commit}, id) {
            commit('request');
            courseService.getCourseById(id).then(
                course => {
                    commit('success', course);
                },
                error => {
                    commit('failure', error);
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
    }
}
