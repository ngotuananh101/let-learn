import { createRouter, createWebHistory } from "vue-router";
import admin from "./admin";
import auth from "./auth";
import home from "./home";
import store from "../store";

const routes = [...home,...admin, ...auth];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;

// redirect to login if not logged in and trying to access a restricted page
router.beforeEach((to, from, next) => {
    // public pages that don't require auth
    const publicPages = ["/auth/login", "/auth/register"];
    // check if current page is a public page and if user is logged in
    const authRequired = !publicPages.includes(to.path);
    if (authRequired) {
        const loggedIn = localStorage.getItem("user");
        if (!loggedIn) {
            return next({
                path: "/auth/login",
            });
        }else{
            if (to.path.includes('admin') && !store.getters['account/isAdmin']) {
                return next({
                    path: "/",
                });
            }
        }
    }
    next();
});
