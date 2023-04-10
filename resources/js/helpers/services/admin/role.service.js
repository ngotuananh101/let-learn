import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const roleService = {
    getRoleList,
    getUserByRole,
    unassignedRole,
    assignedRole,
    getRole,
    updateRole,
    addRole,
    deleteRole
}

function getRoleList() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/admin/role`, requestOptions)
        .then(handleResponse)
}

function getUserByRole(role) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/admin/role/${role}`, requestOptions)
        .then(handleResponse)
}

function unassignedRole(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/admin/role/${data.role_id}`, requestOptions)
        .then(handleResponse)
}

function assignedRole(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/admin/role/${data.role_id}`, requestOptions)
        .then(handleResponse)
}

function getRole(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/admin/role/${id}`, requestOptions)
        .then(handleResponse)
}

function updateRole(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/admin/role/${data.id}`, requestOptions)
        .then(handleResponse)
}

function addRole(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/admin/role`, requestOptions)
        .then(handleResponse)
}

function deleteRole(id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };
    return fetch(`/api/admin/role/${id}`, requestOptions)
        .then(handleResponse)
}
