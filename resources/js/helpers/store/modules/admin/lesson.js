import {adminSetService} from '../../../services/admin/lesson.service';
import overlay from '../../../overlay';

let state = {
    lessons: [],
    status: {}
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, lessons) {
            state.status = {index: true};
            state.lessons = lessons;
        },
        indexFailure(state) {
            state.status = {};
            state.lessons = [];
        }
    },
    actions: {
        index({commit}) {
            commit('indexRequest');
            adminSetService.index()
                .then(
                    lessons => {
                        commit('indexSuccess', lessons);
                    },
                    error => {
                        commit('indexFailure', error);
                    }
                );
        },
        create({dispatch}, data) {
            adminSetService.create(data)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson created', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        importSet({dispatch}, data) {
            dispatch('alert/info', 'Lesson importing', {root: true});
            overlay();
            adminSetService.importSet(data)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson imported', {root: true});
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                ).then(() => {
                    overlay();
                }
            );
        },
        deleteSet({dispatch}, id) {
            adminSetService.deleteSet(id)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson deleted', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        exportSet({dispatch}, id) {
            dispatch('alert/info', 'Lesson exporting', {root: true});
            overlay();
            adminSetService.exportSet(id)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson exported', {root: true});
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                ).then(() => {
                    overlay();
                }
            );
        },
        getSet({dispatch}, id) {
            return adminSetService.getSet(id)
                .then(
                    sets => {
                        return sets;
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                        // return back to previous page
                        window.history.back();
                    }
                );
        },
        updateSet({dispatch}, data) {
            adminSetService.updateSet(data)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson updated', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        }
    },
    getters: {
        lessons: state => {
            return state.lessons;
        }
    }
}
