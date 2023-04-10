const auth = [
    {
        path: '/auth',
        name: 'auth',
        component: () => import('../../../layouts/auth.vue'),
        children: [
            {
                path: 'login',
                name: 'auth.login',
                component: () => import('../../../pages/auth/login.vue'),
            },
            {
                path: 'register',
                name: 'auth.register',
                component: () => import('../../../pages/auth/register.vue'),
            },
            {
                path: 'forgot-password',
                name: 'auth.forgot-password',
                component: () => import('../../../pages/auth/forgot-password.vue'),
            },
            {
                path: 'reset-password',
                name: 'auth.reset-password',
                component: () => import('../../../pages/auth/reset-password.vue'),
            }
        ],
    },
];
export default auth;