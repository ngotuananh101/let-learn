import {adminFolderService} from '../../../services/admin/folder.service';
import overlay from '../../../overlay';

let state = {status: {}, folders: []};

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, folders) {
            state.status = {index: true};
            state.folders = folders;
        },
        indexFailure(state) {
            state.status = {};
            state.folders = [];
        }
    },
    actions: {
        index({commit}) {
            overlay();
            commit('indexRequest');
            return adminFolderService.index()
                .then(
                    folders => {
                        commit('indexSuccess', folders);
                        overlay();
                        return Promise.resolve(folders);
                    },
                    error => {
                        commit('indexFailure', error);
                        overlay();
                        return [];
                    }
                );
        },
        add({commit}, folder) {
            overlay();
            return adminFolderService.add(folder)
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
            adminFolderService.deleteFolder(folder_id).then(() => {
                overlay();
            });
        },
        getFolderById({commit}, folder_id) {
            overlay();
            return adminFolderService.getFolderById(folder_id)
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
            adminFolderService.updateFolder(folder)
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
            return adminFolderService.findSetByName(data)
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
            return adminFolderService.addSetToFolder(data)
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
            return adminFolderService.removeSetFromFolder(data)
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
        folders: state => state.folders,
        status: state => state.status
    }
}
