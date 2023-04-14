import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const learnService = {
    loadFlashCard,
    loadLearn,
    loadTest,
    updateResult
}

function loadFlashCard(id) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify({lesson_id: id})
    };
    return fetch(`/api/user/main?type=detail_split`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}
function loadLearn(id) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify({lesson_id: id, reverse: 1, mix_details: 1, mix_answers: 0})
    };
    return fetch(`/api/user/main?type=learn`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data.lesson_details;
        });
}
function loadTest(id) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify({lesson_id: id,quantity: 20 ,reverse: 1, mix_details: 0, mix_answers: 0})
    };
    return fetch(`/api/user/main?type=test`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data.lesson_details;
        });
}
function updateResult(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify({data})
    };
    return fetch(`/api/user/main/2/?type=learned`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data;
        });
}
