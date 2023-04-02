const classes = [
    {
        path: '/class',
        redirect: {name: 'error'},
        children: [
            {
                path: ':id',
                name: 'class.index',
                component: () => import('../../pages/home/class/Class.vue'),
            },
    ]
    },
];
export default classes;
