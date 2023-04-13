import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const learnService = {
    loadFlashCard,
    loadLearn
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
            console.log(data.data);
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
            console.log(data.lesson_details);
            return data.lesson_details;
        });
}
