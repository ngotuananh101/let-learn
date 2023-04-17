import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const dashboardService = {
    quotes,
    analytics,
};

function quotes() {
    return fetch('https://api.quotable.io/quotes/random?limit=5&minLength=100')
        .then(handleResponse)
        .then(quotes => {
                return quotes;
            }
        );
}

function analytics(type) {
    return fetch(`/api/admin/analytics?type=${type}`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

