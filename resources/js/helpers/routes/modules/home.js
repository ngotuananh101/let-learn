const home = [
    {
        path: '',
        name: 'home',
        component: () => import('../../../pages/home/home.vue'),
        meta: {
            requiresAuth: true,
        }
    },
    // {
    //     path: 'lesson',
    //     children: [
    //         // {
    //         //     path: ':id',
    //         //     name: 'lesson.index',
    //         //     component: () => import('../../../pages/lesson/Lesson.vue'),
    //         // },
    //         // {
    //         //     path: ':id/edit',
    //         //     name: 'lesson.edit',
    //         //     component: () => import('../../pages/lesson/Edit.vue'),
    //         // },
    //         // {
    //         //     path: 'add',
    //         //     name: 'lesson.add',
    //         //     component: () => import('../../pages/lesson/Add.vue'),
    //         // },
    //     ],
    // },
    // {
    //     path: 'course',
    //     children: [
    //         {
    //             path: ':id',
    //             name: 'home.course.index',
    //             component: () => import('../../pages/course/Course.vue'),
    //         },
    //         {
    //             path: ':id/edit',
    //             name: 'home.course.edit',
    //             component: () => import('../../pages/course/Edit.vue'),
    //         },
    //     ],
    // },
];

export default home;
