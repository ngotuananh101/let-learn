import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const commentService = {
    addComment,
    loadComments,
    deleteComment
}

function addComment(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };

    return fetch(`/api/forum/post/${data.post_id}/comment`, requestOptions).then(handleResponse);
}

function loadComments(data) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/forum/post/${data.post_id}/comment?page=${data.page}`, requestOptions).then(handleResponse);
}

function deleteComment(data) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`/api/forum/post/${data.post_id}/comment/${data.comment_id}`, requestOptions).then(handleResponse);
}
