import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';
import {config} from "vue-gtag";

export const homeService = {
    loadHome,
    loadLearn
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
function loadLearn(id) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify({lesson_id: id})
    };
    return fetch(`/api/user/main?type=learn`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data.lesson_details);
            return data.lesson_details;
        });
}
