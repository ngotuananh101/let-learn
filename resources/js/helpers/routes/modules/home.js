const home = [
    {
        path: "/",
        name: "home",
        component: () => import("../../../layouts/home.vue"),
        redirect: { name: "home.home" },
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "",
                name: "home.home",
                component: () => import("../../../pages/home/home.vue"),
            },
            {
                path: "profile",
                name: "home.profile",
                component: () =>
                    import("../../../pages/home/option/profile.vue"),
            },
            {
                path: "setting",
                name: "home.setting",
                component: () =>
                    import("../../../pages/home/option/setting.vue"),
            },
            {
                path: "learn/:id",
                name: "home.learn",
                component: () => import("../../../pages/home/learn/learn.vue"),
            },
            {
                path: "flashcard/:id",
                name: "home.flashcard",
                component: () => import("../../../pages/home/learn/flash_card.vue"),
            },
            {
                path: "test/:id",
                name: "home.test",
                component: () => import("../../../pages/home/learn/test.vue"),
            }
        ],
    },
    {
        path: "/lesson",
        name: "lesson",
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: ":id",
                name: "lesson.index",
                component: () => import("../../../pages/lesson/Lesson.vue"),
            },
            {
                path: "add",
                name: "lesson.add",
                component: () => import("../../../pages/lesson/Add.vue"),
            },
        ],
    },
    {
        path: "/course",
        name: "course",
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: ":id",
                name: "course.index",
                component: () => import("../../../pages/course/Course.vue"),
            },
        ],
    },
];

export default home;
