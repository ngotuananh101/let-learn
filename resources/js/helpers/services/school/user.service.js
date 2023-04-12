import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const userService = {
    show,
}

function show(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/school/users/${slug}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}
