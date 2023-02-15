import { createStore } from "vuex";
import app from "./modules/app.js";
import account from "./modules/account.js";
import alert from "./modules/alert.js";
import dashboard from "./modules/dashboard.js";

export default createStore({
    namespaced: true,
    modules: {
        app,
        account,
        alert,
        dashboard,
    },
});
