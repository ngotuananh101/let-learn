const home = [
    {
        path: '/',
        name: 'home',
        component: () => import('../../layouts/Home.vue'),
        redirect: { name: 'home.index' },
        children: [
            {
                path: '',
                name: 'home.index',
                component: () => import('../../pages/home/Index.vue'),
            },
            {
                path: 'photoshop',
                name: 'home.photoshop',
                component: () => import('../../pages/home/Photoshop.vue'),
            },
            {
                path: 'flashcards',
                name: 'home.flashcards',
                component: () => import('../../pages/home/Flashcards.vue'),
            }
        ],
    },
    {
        path: '/learn',
        name: 'learn',
        component: () => import('../../layouts/Learn.vue'),
    }
];
export default home;
