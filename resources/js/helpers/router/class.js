const classes = [
    {
        path: '/class',
        // component: () => import('../../pages/home/class/class.vue'),
        redirect: {name: 'error'},
        children: [
            {
                path: ':id',
                name: 'class.index',
                component: () => import('../../pages/home/class/class.vue'),
            },
    ]
    },
];
export default classes;
