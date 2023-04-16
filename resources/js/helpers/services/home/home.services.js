import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';


export const homeService = {
    loadHome,
    loadClassDetail,
    loadUserInformation,
    loadForumDetail,
    commentForum,
    updatePassword
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
function loadForumDetail(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/forum/post/${id}`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data.data);
            return data.data;
        });
}
function commentForum(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data),
    };
    console.log(data);
    return fetch(`/api/forum/post/?type=comment`, requestOptions)
        .then(handleResponse)
}

function updatePassword(password) {
    console.log(password);
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(password)
    };

    return fetch(`/api/user/main/${password.id}?type=password`, requestOptions)
        .then(handleResponse)
        .then(password => {
            return password;
        });
}
