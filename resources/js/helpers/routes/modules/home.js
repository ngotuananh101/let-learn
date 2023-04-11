const home = [
    {
        path: '/',
        name: 'home',
        component: () => import('../../../layouts/home.vue'),
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: '',
                name: 'home.home',
                component: () => import('../../../pages/home/home.vue'),
            },
            {
                path: 'profile',
                name: 'home.profile',
                component: () => import('../../../pages/home/option/profile.vue'),
            }
    ],
    },
];

export default home;
