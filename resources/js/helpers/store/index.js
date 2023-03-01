import { createStore } from "vuex";
import meta from "./modules/meta.js";
import app from "./modules/app.js";
import account from "./modules/account.js";
import alert from "./modules/alert.js";
import dashboard from "./modules/dashboard.js";
import adminSetting from "./modules/admin/setting.js";
import adminSet from "./modules/admin/set.js";

export default createStore({
    namespaced: true,
    modules: {
        meta,
        app,
        account,
        alert,
        dashboard,
        adminSetting,
        adminSet,
    },
});
