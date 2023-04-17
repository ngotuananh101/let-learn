import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const dashboardService = {
    baseInfo,
}

function baseInfo(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/school/dashboard?slug=${slug}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}
