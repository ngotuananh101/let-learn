import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const schoolService = {
    getSchoolList,
    addSchool,
    deleteSchool,
    getSchoolById,
    updateSchool
}

function getSchoolList() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/admin/school`, requestOptions)
        .then(handleResponse)
}

function addSchool(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/admin/school`, requestOptions)
        .then(handleResponse)
}

function deleteSchool(id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };
    return fetch(`/api/admin/school/${id}`, requestOptions)
        .then(handleResponse)
}

function getSchoolById(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/admin/school/${id}`, requestOptions)
        .then(handleResponse)
}

function updateSchool(data) {
    // remove class from data
    data = {
        id: data.id,
        name: data.name,
        slug: data.slug,
        website: data.website,
        address: data.address,
        city: data.city,
        region: data.region,
        logo: data.logo,
        status: data.status
    }
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/admin/school/${data.id}`, requestOptions)
        .then(handleResponse)
}
