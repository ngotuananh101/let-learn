import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const classService = {
    index,
    show,
    update,
    getQuizById,
    updateQuiz,
    getPostById,
    deletePost,
    deleteComment,
    deleteQuiz,
    deleteClass,
    store
}

function index(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/school/classes?slug=${slug}`, requestOptions).then(handleResponse);
}

function show(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/school/classes/${id}`, requestOptions).then(handleResponse);
}

function update(id, data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };

    return fetch(`/api/school/classes/${id}`, requestOptions).then(handleResponse);
}

function getQuizById(quiz_id, class_id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/school/classes/${class_id}/edit?quiz_id=${quiz_id}&type=quiz`, requestOptions).then(handleResponse);
}

function updateQuiz(id, data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };

    return fetch(`/api/school/classes/${id}`, requestOptions).then(handleResponse);
}

function getPostById(post_id, class_id, page) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/school/classes/${class_id}/edit?post_id=${post_id}&type=post&page=${page}`, requestOptions).then(handleResponse);
}

function deletePost(post_id, class_id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`/api/school/classes/${class_id}?post_id=${post_id}&type=delete_post`, requestOptions).then(handleResponse);
}

function deleteComment(comment_id, post_id, class_id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`/api/school/classes/${class_id}?post_id=${post_id}&comment_id=${comment_id}&type=delete_comment`, requestOptions).then(handleResponse);
}

function deleteQuiz(quiz_id, class_id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`/api/school/classes/${class_id}?quiz_id=${quiz_id}&type=delete_quiz`, requestOptions).then(handleResponse);
}

function deleteClass(class_id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`/api/school/classes/${class_id}?type=delete_class`, requestOptions).then(handleResponse);
}

function store(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };

    return fetch(`/api/school/classes`, requestOptions).then(handleResponse);
}
