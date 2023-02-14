const auth = [
    {
        path: '/auth',
        name: 'auth',
        component: () => import('../../layouts/Auth.vue'),
        children: [
            {
                path: 'login',
                name: 'auth.login',
                component: () => import('../../pages/auth/Login.vue'),
            },
            {
                path: 'register',
                name: 'auth.register',
                component: () => import('../../pages/auth/Register.vue'),
            },
            {
                path: 'logout',
                name: 'auth.logout',
                component: () => import('../../pages/auth/Logout.vue'),
            }
        ],
    },
];
export default auth;
