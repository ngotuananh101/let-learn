import handleResponse from '../other/handle-response';
import authHeader from '../other/auth-header';

export const userService = {
    login,
    register,
    forgotPassword,
    resetPassword,
    logout,
};

function login(email, password, remember) {
    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password, remember })
    };
    return fetch(`/api/auth/login`, requestOptions)
        .then(handleResponse)
        .then(response => {
            localStorage.setItem('userData', JSON.stringify(response));
            return response;
        });
}

function register(user) {
    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(user)
    };
    return fetch(`/api/auth/register`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function forgotPassword(email) {
    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
    };
    return fetch(`/api/auth/forgot-password`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function resetPassword(data) {
    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    };
    return fetch(`/api/auth/reset-password`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function logout(type = 'current') {
    const requestOptions = {
        method: 'POST',
        headers: authHeader()
    };
    console.log(authHeader());
    return fetch(`/api/auth/logout?type=${type}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            localStorage.clear();
            return response;
        });
}
