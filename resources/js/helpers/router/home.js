const home = [
    {
        path: '/',
        name: 'home',
        component: () => import('../../layouts/Home.vue'),
        redirect: {name: 'home.index'},
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
                path: 'profile',
                name: 'home.profile',
                component: () => import('../../pages/home/Profile.vue'),
                children: [
                    {
                        path: 'lessons',
                        name: 'home.profile.lessons',
                        component: () => import('../../pages/home/ProfileLesson.vue'),
                    },
                    {
                        path: 'courses',
                        name: 'home.profile.courses',
                        component: () => import('../../pages/home/ProfileCourse.vue'),
                    },
                    {
                        path: 'classes',
                        name: 'home.profile.classes',
                        component: () => import('../../pages/home/ProfileClass.vue'),
                    }
                ],
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
