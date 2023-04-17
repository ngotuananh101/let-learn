import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';


export const homeService = {
    loadHome,
    loadClassDetail,
    loadUserInformation,
    loadForumDetail,
    commentForum,
    loadForum,
    AddQuestionForum,
    voteComment,
    voteQuestion,
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
function loadForum() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/forum/post/`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data.posts);
            return data.posts;
        });
}
function AddQuestionForum(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data),
    };
    console.log(data);
    return fetch(`/api/forum/post/?type=post`, requestOptions)
        .then(handleResponse)
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
function voteComment(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data),
    };
    console.log(data);
    return fetch(`/api/forum/post/${data.id}?type=comment&choice=vote`, requestOptions)
        .then(handleResponse)
}
function voteQuestion(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data),
    };
    console.log(data);
    return fetch(`/api/forum/post/${data.id}?type=post&choice=like`, requestOptions)
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
