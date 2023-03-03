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
                path: 'users',
                name: 'admin.users',
                component: () => import('../../pages/admin/users/Index.vue'),
            },
            {
                path: 'roles',
                name: 'admin.roles',
                component: () => import('../../pages/admin/roles/Index.vue'),
            },
            {
                path: 'set',
                name: 'admin.set',
                redirect: {name: 'admin.set.list'},
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
            },
            {
                path: 'folder',
                name: 'admin.folder',
                redirect: {name: 'admin.folder.list'},
                children: [
                    {
                        path: 'list',
                        name: 'admin.folder.list',
                        component: () => import('../../pages/admin/folder/List.vue'),
                    },
                    {
                        path: 'edit/:id',
                        name: 'admin.folder.edit',
                        component: () => import('../../pages/admin/folder/Edit.vue'),
                    }
                ]
            }
        ],
    },
];

export default admin;
