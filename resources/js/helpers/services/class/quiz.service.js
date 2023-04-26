import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const quizService = {
    addQuiz,
    getQuizByClassId,
    getQuizById,
    updateQuiz,
    deleteQuiz,
    viewReport,
    updateScore
}

function addQuiz(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/classes/${data.class_id}/quizzes`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function getQuizByClassId(classId) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/classes/${classId}/quizzes`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function getQuizById(classId,quizId) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/classes/${classId}/quizzes/${quizId}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function updateQuiz(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/classes/${data.class_id}/quizzes/${data.quiz_id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function deleteQuiz(data) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };
    return fetch(`/api/classes/${data.class_id}/quizzes/${data.quiz_id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function viewReport(data) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/classes/${data.class_id}/quizzes/${data.quiz_id}?type=report`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function updateScore(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/classes/${data.class_id}/quizzes/${data.quiz_id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}
