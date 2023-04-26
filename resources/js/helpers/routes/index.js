import {createRouter, createWebHistory} from "vue-router";
import store from "../stores";
import auth from "./modules/auth";
import home from "./modules/home";
import admin from "./modules/admin";
import school from "./modules/school";

const routes = [...auth, ...home, ...admin, ...school];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;

router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(route => route.meta.requiresAuth);
    const userData = store.getters['user/userData'];
    // Check if route requires auth
    if (requiresAuth) {
        // Check if user is logged in
        if (!userData) {
            // Redirect to login page
            next({name: 'auth.login'});
        } else {
            const permissions = userData?.info?.role?.permissions;
            // check if user try to access admin page
            if (to.matched.some(route => route.meta?.requiresAdmin)) {
                // Check if user is admin
                if (permissions.filter(permission => (permission.name === 'admin.dashboard' || permission.name === 'admin.super')).length > 0) {
                    next();
                } else {
                    next({name: 'home'});
                }
            } else if (to.matched.some(route => route.meta?.requiresManage)) {
                // Check if user is manager
                if (permissions.filter(permission => (permission.name === 'admin.super' || permission.name === 'manager.dashboard')).length > 0) {
                    if (permissions.filter(permission => (permission.name === 'admin.dashboard' || permission.name === 'admin.super')).length > 0){
                        next();
                    }else if (permissions.filter(permission => (permission.name === 'manager.dashboard')).length > 0 && userData.info.school.slug === to.params.slug) {
                        next();
                    }else{
                        next({name: 'home'});
                    }
                } else {
                    next({name: 'home'});
                }
            } else {
                next();
            }
        }
    } else {
        if (userData) {
            next({name: 'home'});
        } else {
            next();
        }
    }
});
