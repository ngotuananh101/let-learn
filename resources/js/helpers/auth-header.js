export function authHeader() {
    let user = JSON.parse(localStorage.getItem('user'));
    let accessToken = localStorage.getItem('access_token');

    if (user && accessToken) {
        return {
            'Authorization': 'Bearer ' + user.token,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
    } else {
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };
    }
}
