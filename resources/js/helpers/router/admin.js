const admin = [
    {
        path: '/admin',
        name: 'admin',
        component: () => import('../../layouts/Admin.vue'),
        children: [
            {
                path: 'users',
                name: 'admin.users',
                component: () => import('../../pages/admin/users/Index.vue'),
            },
        ],
    },
];

export default admin;
