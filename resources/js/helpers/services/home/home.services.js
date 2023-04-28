import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';


export const homeService = {
    loadHome,
    loadClassDetail,
    loadUserInformation,
    updatePassword,
    changeInfor,
    search

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

function loadUserInformation(page) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/public/information?page=${page}`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data;
        });
}

function loadClassDetail(class_id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`/api/classes/${class_id}/members`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data;
        });
}

function changeInfor(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data),
    };
    return fetch(`/api/${data.role}/main/${data.id}?type=info`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data.message;
        });
}

function updatePassword(password) {
    console.log(password);
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(password)
    };

    return fetch(`/api/${password.roleName}/main/${password.id}?type=password`, requestOptions)
        .then(handleResponse)
        .then(password => {
            return password;
        });
}

function search(query) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`/api/public/search?query=${query}`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data;
        });
}
