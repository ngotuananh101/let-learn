const school = [
    {
        path: '/school',
        name: 'school',
        component: () => import('../../../layouts/school.vue'),
        redirect: {name: 'school.index'},
        children: [
            {
                path: '',
                name: 'school.index',
                component: () => import('../../../pages/school/dashboard.vue'),
            }
        ],
        meta: {
            requiresAuth: true,
            requiresManage: true,
        }
    }
]

export default school;
