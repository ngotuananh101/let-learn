import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminFolderService = {
    index,
    add,
    deleteFolder
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
