
const learn = [
    {
        path: '/learn',
        component: () => import('../../layouts/Learn.vue'),
        redirect: {name: 'error'},
        children: [
            {
                path: 'flashcard/:id',
                name: 'learn.flashcard.index',
                component: () => import('../../pages/learn/Flashcards.vue'),
            },
            {
                path: ':id',
                name: 'learn.quiz.index',
                component: () => import('../../pages/learn/Learn.vue'),
            },
            {
                path: 'test/:id',
                name: 'learn.test.index',
                component: () => import('../../pages/learn/Test.vue'),
            }
        ]
    },
];
export default learn;
