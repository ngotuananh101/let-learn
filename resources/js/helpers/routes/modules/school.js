import store from "../../stores";

const school = [
    {
        path: '/school',
        name: 'school',
        redirect: () => {
            const userData = store.getters['user/userData'];
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
                    }
                ],
            }
        ],
        meta: {
            requiresAuth: true,
            requiresManage: true,
        }
    }
]

export default school;
