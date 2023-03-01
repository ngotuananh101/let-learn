const home = [
    {
        path: '/',
        name: 'home',
        component: () => import('../../layouts/Home.vue'),
        children: [
            {
                path: '/photoshop',
                name: 'home.photoshop',
                component: () => import('../../pages/home/Photoshop.vue'),
            }
        ],
    },
];
export default home;
