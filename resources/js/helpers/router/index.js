import { createRouter, createWebHistory } from "vue-router";
import admin from "./admin";
import auth from "./auth";

const routes = [...admin, ...auth];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;

// redirect to login if not logged in and trying to access a restricted page
router.beforeEach((to, from, next) => {
    const publicPages = ["/auth/login", "/auth/register"];
    const authRequired = !publicPages.includes(to.path);
    const loggedIn = localStorage.getItem("user");
    if (authRequired && !loggedIn) {
        return next("/auth/login");
    }
    next();
});
