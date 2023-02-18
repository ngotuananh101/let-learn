import config from '../config';
import handleResponse from './handle-response';
import { authHeader } from '../auth-header';

export const userService = {
    login,
    logout,
    register,
    forgotPassword,
    resetPassword,
    verifyEmail,
    resendVerificationEmail
};

function login(email, password, rememberMe) {
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({email, password, rememberMe})
    };
    return fetch(`${config.apiUrl}/auth/login`, requestOptions)
        .then(handleResponse)
        .then(user => {
            // Login successful if status is success
            if (user.status === 'success') {
                // Store user details and bearer token in local storage to keep user logged in between page refreshes
                localStorage.setItem('user', JSON.stringify(user.data.user));
                localStorage.setItem('token', JSON.stringify(user.data.access_token));
            }
            return user;
        });
}

function logout() {
    // remove user from local storage to log user out
    localStorage.removeItem('user');
    localStorage.removeItem('token');
}

function register(user) {
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(user)
    };

    return fetch(`${config.apiUrl}/auth/register`, requestOptions)
        .then(handleResponse)
        .then(user => {
            // login after successful registration
            if (user.status === 'success') {
                // Store user details and bearer token in local storage to keep user logged in between page refreshes
                localStorage.setItem('user', JSON.stringify(user.data.user));
                localStorage.setItem('token', JSON.stringify(user.data.access_token));
            }

            return user;
        });
}

function forgotPassword(email) {
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({email: email})
    };

    return fetch(`${config.apiUrl}/auth/forgot-password`, requestOptions)
        .then(handleResponse)
        .then(response => {
                return response;
            }
        );
}

function resetPassword(email, password, password_confirmation, token) {
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            email: email,
            password: password,
            password_confirmation: password_confirmation,
            token: token
        })
    };

    return fetch(`${config.apiUrl}/auth/reset-password`, requestOptions)
        .then(handleResponse)
        .then(response => {
                return response;
            }
        );
}

function verifyEmail(token) {
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({token: token})
    };

    return fetch(`${config.apiUrl}/auth/verify-email`, requestOptions)
        .then(handleResponse)
        .then(response => {
                return response;
            }
        );
}

function resendVerificationEmail() {
    return fetch(`${config.apiUrl}/auth/resend`, {method: 'POST', headers: authHeader()})
        .then(handleResponse)
        .then(response => {
                return response;
            }
        );
}
