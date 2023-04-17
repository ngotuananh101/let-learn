const admin = [
    {
        path: '/admin',
        name: 'admin',
        redirect: {name: 'admin.dashboard'},
        component: () => import('../../../layouts/admin.vue'),
        children: [
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: () => import('../../../pages/admin/dashboard.vue'),
            },
            {
                path: 'setting',
                name: 'admin.setting',
                component: () => import('../../../pages/admin/setting.vue'),
            },
            {
                path: 'notification',
                name: 'admin.notification',
                component: () => import('../../../pages/admin/notification.vue'),
            },
            {
                path: 'user',
                name: 'admin.user',
                component: () => import('../../../pages/admin/user.vue'),
            },
            {
                path: 'role',
                name: 'admin.role',
                component: () => import('../../../pages/admin/role.vue'),
            },
            {
                path: 'school',
                name: 'admin.school',
                redirect: {name: 'admin.school.index'},
                children: [
                    {
                        path: '',
                        name: 'admin.school.index',
                        component: () => import('../../../pages/admin/school/index.vue'),
                    },
                    {
                        path: ':id/edit',
                        name: 'admin.school.edit',
                        component: () => import('../../../pages/admin/school/edit.vue'),
                    }
                ],
            },
            {
                path: 'lesson',
                name: 'admin.lesson',
                redirect: {name: 'admin.lesson.index'},
                children: [
                    {
                        path: '',
                        name: 'admin.lesson.index',
                        component: () => import('../../../pages/admin/lesson/index.vue'),
                    },
                    {
                        path: ':id/edit',
                        name: 'admin.lesson.edit',
                        component: () => import('../../../pages/admin/lesson/edit.vue'),
                    },
                    {
                        path: 'add',
                        name: 'admin.lesson.add',
                        component: () => import('../../../pages/admin/lesson/add.vue'),
                    }
                ],
            },
            {
                path: 'course',
                name: 'admin.course',
                redirect: {name: 'admin.course.index'},
                children: [
                    {
                        path: '',
                        name: 'admin.course.index',
                        component: () => import('../../../pages/admin/course/index.vue'),
                    },
                    {
                        path: ':id/edit',
                        name: 'admin.course.edit',
                        component: () => import('../../../pages/admin/course/edit.vue'),
                    }
                ],
            }
        ],
        meta: {
            requiresAuth: true,
            requiresAdmin: true,
        }
    },
];
export default admin;
