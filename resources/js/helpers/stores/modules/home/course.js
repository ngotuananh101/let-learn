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
        getLessonByCourseId({ commit }, payload) {
            commit("request");
            console.log(id, roleName);
            courseService.getLessonByCourseId(payload.id, payload.roleName).then(
                (response) => {
                    commit("success", response);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        addCourse({ commit }, course) {
            commit("request");
            courseService.addCourse(course).then(
                (course) => {
                    commit("success", course);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        updateCourse({ commit }, course) {
            commit("request");
            courseService.updateCourse(course).then(
                (course) => {
                    commit("success", course);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        deleteCourse({ commit }, id, roleName) {
            commit("request");
            courseService.deleteCourse(id, roleName).then(
                (course) => {
                    commit("success", course);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
    },
};
