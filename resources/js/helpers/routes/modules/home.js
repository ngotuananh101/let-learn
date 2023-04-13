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
            },
            {
                path: "forum_detail/:id",
                name: "home.forum_detail",
                component: () => import("../../../pages/home/forum/forum_detail.vue"),
            },
            {
                path: "forum",
                name: "home.forum",
                component: () => import("../../../pages/home/forum/forum.vue"),
            }
        ],
    },
];

export default home;
