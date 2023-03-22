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
                path: 'l/:id',
                name: 'home.lesson.index',
                component: () => import('../../pages/home/lesson/Lesson.vue'),
            },
            {
                path: 'c/:id',
                name: 'home.course.index',
                component: () => import('../../pages/home/course/Course.vue'),
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
                        name: 'home.profile.class',
                        component: () => import('../../pages/home/ProfileClass.vue'),
                    }
                ],
            },
            {
                path: 'setting',
                name: 'home.setting',
                component: () => import('../../pages/home/Setting.vue'),
            },
            {
                path: 'flashcards',
                name: 'home.flashcards',
                component: () => import('../../pages/learn/Flashcards.vue'),
            }
        ],
    },
];
export default home;
