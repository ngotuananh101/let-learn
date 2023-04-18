import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const classService = {
    index,
    show
}

function index(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/school/classes?slug=${slug}`, requestOptions).then(handleResponse);
}

function show(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/school/classes/${id}`, requestOptions).then(handleResponse);
}
