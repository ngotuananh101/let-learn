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
            },
            {
                path: 'set',
                name: 'admin.set',
                redirect: {name: 'admin.set.list'},
                component: () => import('../../pages/admin/set/Set.vue'),
                children: [
                    {
                        path: 'list',
                        name: 'admin.set.list',
                        component: () => import('../../pages/admin/set/List.vue'),
                    },
                    {
                        path: 'add',
                        name: 'admin.set.add',
                        component: () => import('../../pages/admin/set/Add.vue'),
                    },
                    {
                        path: 'edit/:id',
                        name: 'admin.set.edit',
                        component: () => import('../../pages/admin/set/Edit.vue'),
                    }
                ]
            }
        ],
    },
];

export default admin;
