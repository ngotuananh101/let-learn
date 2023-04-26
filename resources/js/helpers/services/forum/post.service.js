import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const postService = {
    loadPost,
    addPost,
    likePost,
    updatePost,
    deletePost,
    getPostById
}

function loadPost(page = 1) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch('/api/forum/post?page=' + page, requestOptions).then(handleResponse);
}

function addPost(post) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(post)
    };

    return fetch('/api/forum/post', requestOptions).then(handleResponse);
}

function likePost(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };

    return fetch(`/api/forum/post/${data.post_id}`, requestOptions).then(handleResponse);
}

function updatePost(post) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(post)
    };

    return fetch(`/api/forum/post/${post.post_id}`, requestOptions).then(handleResponse);
}

function deletePost(post) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`/api/forum/post/${post.post_id}`, requestOptions).then(handleResponse);
}

function getPostById(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/forum/post/${id}`, requestOptions).then(handleResponse);
}
