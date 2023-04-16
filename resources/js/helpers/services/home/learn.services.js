import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const learnService = {
    loadFlashCard,
    loadLearn,
    loadTest,
    updateResult,
    sendTestResult,
    loadSelfTest,
    updateComment
}

function loadFlashCard(id) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify({lesson_id: id})
    };
    return fetch(`/api/student/main?type=detail_split`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data.data;
        });
}
function loadLearn(id) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify({lesson_id: id,quantity: 8 ,reverse: 1, mix_details: 0, mix_answers: 0})
    };
    return fetch(`/api/student/main?type=test`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data.lesson_details;
        });
}
function loadTest(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`/api/student/quiz/${id}?type=question`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}
function loadSelfTest(id) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify({lesson_id: id,quantity: 20 ,reverse: 1, mix_details: 0, mix_answers: 0}),
    };
    return fetch(`/api/student/main?type=test`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data.lesson_details;
        });
}
function sendTestResult(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data),
    };
    return fetch(`/api/student/quiz?type=answer&quiz_id=${data.id}`, requestOptions)
        .then(handleResponse)
}
function updateResult(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify({
            lesson_id: data.lesson_id,
            learned: data.learned,
            relearn: data.relearn,
        }),
    };
    return fetch(`/api/student/main/${data.user_id}/?type=learned`, requestOptions)
        .then(handleResponse)
}
function updateComment(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify({
            comment_id: data.comment_id,
            comment: data.comment,
            status: data.status,
            votetype: data.votetype,
        }),
    };
    return fetch(`/api/forum/post/1?type=comment`, requestOptions)
        .then(handleResponse)
}

