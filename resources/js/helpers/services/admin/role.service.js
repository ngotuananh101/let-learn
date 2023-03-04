import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminRoleService = {
    index,
    getRoleUsers,
    getRoleInfo,
    getAllPermission,
    addRole,
    updateRole,
    deleteRole
}

function index() {
    return fetch(`${config.apiUrl}/admin/role`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function getRoleUsers(id) {
    let type = 'users';
    return fetch(`${config.apiUrl}/admin/role/${id}?type=${type}`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function getRoleInfo(id) {
    return fetch(`${config.apiUrl}/admin/role/${id}/edit`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function getAllPermission() {
    return fetch(`${config.apiUrl}/admin/role?type=all_permissions`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function addRole(role) {
    return fetch(`${config.apiUrl}/admin/role`, {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(role)
    }).then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function updateRole(role) {
    return fetch(`${config.apiUrl}/admin/role/${role.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(role.data)
    }).then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function deleteRole(id) {
    return fetch(`${config.apiUrl}/admin/role/${id}`, {
        method: 'DELETE',
        headers: authHeader()
    }).then(handleResponse);
}
