import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const postService = {
    getPostsByClassId,
    loadMorePost,
    addComment,
    loadComments,
    addPost,
    deleteComment,
    deletePost,
    updatePost
}

function getPostsByClassId(classId) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/classes/${classId}/posts`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function addComment(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/classes/${data.class_id}/posts/${data.post_id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function loadComments(data) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`/api/classes/${data.class_id}/posts/${data.post_id}?type=load_comment&page=${data.page}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function addPost(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/classes/${data.class_id}/posts`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function deleteComment(data) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/classes/${data.class_id}/posts/${data.post_id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function loadMorePost(url) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(url, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function deletePost(data) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/classes/${data.class_id}/posts/${data.post_id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function updatePost(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/classes/${data.class_id}/posts/${data.post_id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}
