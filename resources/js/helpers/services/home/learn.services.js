import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const learnService = {
    loadFlashCard,
    loadLearn,
    loadTest,
    updateResult,
    sendTestResult
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
        body: JSON.stringify({lesson_id: id, reverse: 1, mix_details: 1, mix_answers: 0})
    };
    return fetch(`/api/student/main?type=learn`, requestOptions)
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
            console.log(data.data);
            return data.data;
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
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify({data})
    };
    return fetch(`/api/student/main/2/?type=learned`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data;
        });
}
