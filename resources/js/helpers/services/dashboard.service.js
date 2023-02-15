import config from '../config';
import { authHeader } from '../auth-header';
import handleResponse from './handle-response';

export const dashboardService = {
    quotes,
    basicInfo,
    analytics,
};

function quotes() {
    return fetch('https://quote-garden.onrender.com/api/v3/quotes?limit=3')
        .then(handleResponse)
        .then(quotes => {
            return quotes;
        }
    );
}

function basicInfo() {
    return fetch(`${config.apiUrl}/dashboard/basic-info`, authHeader)
        .then(handleResponse)
        .then(data => {
            return data;
        });
}

function analytics() {
    return fetch(`${config.apiUrl}/analytics`, authHeader)
        .then(handleResponse)
        .then(data => {
            return data;
        });
}