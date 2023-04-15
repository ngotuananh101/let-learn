import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';


export const homeService = {
    loadHome,
    loadClassDetail,
    loadUserInformation,
};

function loadHome() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/public/home`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data;
        });
};
function loadUserInformation() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/public/information`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data;
        });
}
function loadClassDetail(id, roleName) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`/api/${roleName}/quiz/${id}?type=all`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data.data);
            return data.data;
        });
}

