import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminFolderService = {
    index,
    add,
    deleteFolder,
    getFolderById,
    updateFolder,
    findSetByName,
    addSetToFolder,
    removeSetFromFolder
}

function index() {
    return fetch(`${config.apiUrl}/admin/folder`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function add(folder) {
    return fetch(`${config.apiUrl}/admin/folder`, {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(folder)
    })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function deleteFolder(folder_id) {
    return fetch(`${config.apiUrl}/admin/folder/${folder_id}`, {
        method: 'DELETE',
        headers: authHeader()
    }).then(handleResponse);
}

function getFolderById(folder_id) {
    return fetch(`${config.apiUrl}/admin/folder/${folder_id}/edit`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function updateFolder(folder) {
    folder.type = 'update_folder';
    return fetch(`${config.apiUrl}/admin/folder/${folder.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(folder)
    })
        .then(handleResponse);
}

function findSetByName(data) {
    data.type = 'find_set';
    return fetch(`${config.apiUrl}/admin/folder/${data.folder_id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    })
        .then(handleResponse)
        .then(data => {
                return data.data;
            }
        );
}

function addSetToFolder(data) {
    data.type = 'add_set';
    return fetch(`${config.apiUrl}/admin/folder/${data.folder_id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    })
        .then(handleResponse)
        .then(data => {
                return data.data;
            }
        );
}

function removeSetFromFolder(data) {
    data.type = 'remove_set';
    return fetch(`${config.apiUrl}/admin/folder/${data.folder_id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    })
        .then(handleResponse);
}
