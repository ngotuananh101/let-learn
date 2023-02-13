import config from '../config';

export const userService = {
    login,
    logout
};

function login(email, password, rememberMe) {
    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password, rememberMe})
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

function handleResponse(response) {
    return response.text().then(text => {
        const data = text && JSON.parse(text);
        if (!response.ok) {
            // if (response.status === 401) {
            //     logout();
            //     location.reload(true);
            // }
            const error = (data && data.message) || response.statusText;
            return Promise.reject(error);
        }

        return data;
    });
}
