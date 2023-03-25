import {adminCourseService} from '../../../services/admin/course.service';
import overlay from '../../../overlay';

let state = {status: {}, courses: []};

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, courses) {
            state.status = {index: true};
            state.courses = courses;
        },
        indexFailure(state) {
            state.status = {};
            state.courses = [];
        }
    },
    actions: {
        index({commit}) {
            adminCourseService.index()
                .then(
                    courses => {
                        commit('indexSuccess', courses);
                    },
                    error => {
                        commit('indexFailure', error);
                    }
                );
        },
        add({commit}, folder) {
            overlay();
            return admincourseservice.add(folder)
                .then(
                    folder => {
                        overlay();
                        return Promise.resolve(folder);
                    },
                    error => {
                        overlay();
                        return [];
                    }
                );
        },
        delete({commit}, folder_id) {
            overlay();
            admincourseservice.deleteFolder(folder_id).then(() => {
                overlay();
            });
        },
        getFolderById({commit}, folder_id) {
            overlay();
            return admincourseservice.getFolderById(folder_id)
                .then(
                    folder => {
                        overlay();
                        return Promise.resolve(folder);
                    },
                    error => {
                        overlay();
                        return {
                            folder: {},
                            sets: []
                        }
                    }
                );
        },
        updateFolder({dispatch}, folder) {
            overlay();
            admincourseservice.updateFolder(folder)
                .then(
                    folder => {
                        overlay();
                        dispatch('alert/success', 'Course updated', {root: true});
                    },
                    error => {
                        overlay();
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        findSetByName({commit}, data) {
            overlay();
            return admincourseservice.findSetByName(data)
                .then(
                    sets => {
                        overlay();
                        return Promise.resolve(sets);
                    },
                    error => {
                        overlay();
                        return [];
                    }
                );
        },
        addSetToFolder({dispatch}, data) {
            overlay();
            return admincourseservice.addSetToFolder(data)
                .then(
                    folder => {
                        overlay();
                        dispatch('alert/success', 'Lesson added to folder', {root: true});
                        return Promise.resolve(folder);
                    },
                    error => {
                        overlay();
                        dispatch('alert/error', error, {root: true});
                        return [];
                    }
                );
        },
        removeSetFromFolder({dispatch}, data) {
            overlay();
            return admincourseservice.removeSetFromFolder(data)
                .then(
                    folder => {
                        overlay();
                        dispatch('alert/success', 'Lesson removed from folder', {root: true});
                        return Promise.resolve(folder);
                    },
                    error => {
                        overlay();
                        dispatch('alert/error', error, {root: true});
                        return [];
                    }
                );
        }
    },
    getters: {
        courses: state => state.courses,
        status: state => state.status
    }
}
