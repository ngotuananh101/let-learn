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
                path: "class/:id",
                name: "home.class",
                component: () => import("../../../pages/home/class.vue"),
            },
            {
                path: "class/:id/test/add",
                name: "home.test.add",
                component: () =>
                    import("../../../pages/home/option/add_test.vue"),
            },
            {
                path: "/lesson",
                meta: {
                    requiresAuth: true,
                },
                children: [
                    {
                        path: ":id",
                        name: "lesson.index",
                        component: () =>
                            import("../../../pages/lesson/Lesson.vue"),
                    },
                    {
                        path: ":id/edit",
                        name: "lesson.edit",
                        component: () =>
                            import("../../../pages/lesson/Edit.vue"),
                    },
                    {
                        path: "add",
                        name: "lesson.add",
                        component: () =>
                            import("../../../pages/lesson/Add.vue"),
                    },
                    {
                        path: "learn/:id",
                        name: "home.learn",
                        component: () =>
                            import("../../../pages/home/learn/learn.vue"),
                    },
                    {
                        path: "flashcard/:id",
                        name: "home.flashcard",
                        component: () =>
                            import("../../../pages/home/learn/flash_card.vue"),
                    },
                    {
                        path: "selftest/:id",
                        name: "home.selftest",
                        component: () =>
                            import("../../../pages/home/learn/self_test.vue"),
                    },
                    {
                        path: "test/:id",
                        name: "home.test",
                        component: () =>
                            import("../../../pages/home/learn/test.vue"),
                    },
                ],
            },
            {
                path: "/forum",
                name: "forum",
                meta: {
                    requiresAuth: true,
                },
                children: [
                    {
                        path: "",
                        name: "forum",
                        component: () =>
                            import("../../../pages/home/forum/forum.vue"),
                    },
                    {
                        path: "forum_detail/:id",
                        name: "forum.forum_detail",
                        component: () =>
                            import(
                                "../../../pages/home/forum/forum_detail.vue"
                                ),
                    },
                ]
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
                        component: () =>
                            import("../../../pages/home/course/Course.vue"),
                    },
                    {
                        path: ":id/edit",
                        name: "course.edit",
                        component: () =>
                            import("../../../pages/home/course/Edit.vue"),
                    },
                ],
            },
        ],
    },
];
export default home;
