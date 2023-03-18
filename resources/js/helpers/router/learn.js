

const learn = [
    {
        path: '/learn',
        component: () => import('../../layouts/Learn.vue'),
        redirect: {name: 'error'},
        children: [
            {
                path: ':id',
                name: 'learn.index',
                component: () => import('../../pages/learn/Flashcards.vue'),
            },
        ]
    },
];
export default learn;
