export function authHeader() {
    let user = JSON.parse(localStorage.getItem('user'));
    let accessToken = localStorage.getItem('access_token');

    if (user && accessToken) {
        return { 'Authorization': 'Bearer ' + user.token };
    } else {
        return {};
    }
}
