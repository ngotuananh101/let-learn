let meta = JSON.parse(localStorage.getItem('meta_data'));
const admin = [
    {
        path: '/admin',
        name: 'admin',
        redirect: {name: 'admin.dashboard'},
        component: () => import('../../layouts/Admin.vue'),
        children: [
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: () => import('../../pages/admin/Dashboard.vue'),
            },
            {
                path: 'setting',
                name: 'admin.setting',
                component: () => import('../../pages/admin/setting/Setting.vue'),
            }
        ],
    },
];

export default admin;
