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
        }
    },
    getters: {
        folders: state => state.folders,
        status: state => state.status
    }
}
