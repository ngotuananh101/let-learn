import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const classService = {
    index,
}

function index(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/school/classes?slug=${slug}`, requestOptions).then(handleResponse);
}
