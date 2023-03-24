const classes = [
    {
        path: '/class',
        redirect: {name: 'error'},
        children: [
            {
                path: ':id',
                name: 'class.js.index',
                component: () => import('../../pages/home/class/Class.vue'),
            },
            {
                path: 'detail/:id',
                name: 'exerciseDetail.js.index',
                component: () => import('../../pages/home/class/ExerciseDetail.vue'),
            }
    ]
    },
];
export default classes;
