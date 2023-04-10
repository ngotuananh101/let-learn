const home = [
    {
        path: '',
        name: 'home',
        component: () => import('../../../pages/home/home.vue'),
        meta: {
            requiresAuth: true,
        }
    }
];

export default home;