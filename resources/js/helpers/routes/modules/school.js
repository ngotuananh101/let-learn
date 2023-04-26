import store from "../../stores";

const school = [
    {
        path: '/school',
        name: 'school',
        meta: {
            requiresAuth: true,
            requiresManage: true,
        },
        redirect: () => {
            const userData = store.getters['user/userData'];
            if(!userData){
                return {name: 'auth.login'};
            }
            if (!userData.info.school && !userData.info.role.permissions.filter(permission => (permission.name === 'admin.dashboard' || permission.name === 'admin.super')).length > 0) {
                return {name: 'home'};
            }
            if(!userData.info.school){
                return {name: 'home'};
            }
            return {name: 'school.index', params: {slug: userData.info.school.slug}};
        },
        children: [
            {
                path: ':slug',
                name: 'school.index',
                component: () => import('../../../layouts/school.vue'),
                redirect: {name: 'school.dashboard'},
                children: [
                    {
                        path: '',
                        name: 'school.dashboard',
                        component: () => import('../../../pages/school/dashboard.vue'),
                    },
                    {
                        path: 'setting',
                        name: 'school.setting',
                        component: () => import('../../../pages/school/setting.vue'),
                    },
                    {
                        path: 'users',
                        name: 'school.users',
                        component: () => import('../../../pages/school/users.vue'),
                    },
                    {
                        path: 'lesson',
                        name: 'school.lesson',
                        redirect: {name: 'school.lesson.index'},
                        children: [
                            {
                                path: '',
                                name: 'school.lesson.index',
                                component: () => import('../../../pages/school/lesson/index.vue'),
                            },
                            {
                                path: ':id/edit',
                                name: 'school.lesson.edit',
                                component: () => import('../../../pages/school/lesson/edit.vue'),
                            },
                            {
                                path: 'create',
                                name: 'school.lesson.add',
                                component: () => import('../../../pages/school/lesson/add.vue'),
                            }
                        ]
                    },
                    {
                        path: 'course',
                        name: 'school.course',
                        redirect: {name: 'school.course.index'},
                        children: [
                            {
                                path: '',
                                name: 'school.course.index',
                                component: () => import('../../../pages/school/course/index.vue'),
                            },
                            {
                                path: ':id/edit',
                                name: 'school.course.edit',
                                component: () => import('../../../pages/school/course/edit.vue'),
                            }
                        ]
                    },
                    {
                        path: 'class',
                        name: 'school.class',
                        redirect: {name: 'school.class.index'},
                        children: [
                            {
                                path: '',
                                name: 'school.class.index',
                                component: () => import('../../../pages/school/class/index.vue'),
                            },
                            {
                                path: ':id/edit',
                                name: 'school.class.edit',
                                component: () => import('../../../pages/school/class/edit.vue'),
                            }
                        ]
                    }
                ],
            }
        ]
    }
]

export default school;
