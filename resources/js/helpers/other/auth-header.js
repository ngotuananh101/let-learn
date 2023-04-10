import store from '../stores';
function authHeader(contentType = 'application/json', accept = 'application/json') {
    const userData = store.getters['user/userData'];
    const user = userData?.info;
    const accessToken = userData?.access_token;
    if (user && accessToken) {
        return {
            'Authorization': 'Bearer ' + accessToken,
            'Content-Type': contentType,
            'Accept': accept,
        };
    } else {
        return {
            'Content-Type': contentType,
            'Accept': accept,
        };
    }
}
export default authHeader;
