function handleResponse(response) {
    return response.text().then(text => {
        const data = text && JSON.parse(text);
        if (!response.ok) {
            if (response.status === 403) {
                localStorage.clear();
                location.replace('/auth/login');
            } else if (response.status === 401) {
                // get curren url
                const currentUrl = window.location.href;
                if (!currentUrl.includes('/auth/login')) {
                    location.replace('/auth/login');
                }
                localStorage.clear();
            }
            const error = (data && data.message) || response.statusText;
            return Promise.reject(error);
        }
        return data;
    });
}

export default handleResponse;
