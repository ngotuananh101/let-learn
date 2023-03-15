import { createStore } from "vuex";
import meta from "./modules/meta.js";
import app from "./modules/app.js";
import account from "./modules/account.js";
import alert from "./modules/alert.js";
import dashboard from "./modules/dashboard.js";
import adminSetting from "./modules/admin/setting.js";
import adminSet from "./modules/admin/set.js";
import adminFolder from "./modules/admin/folder.js";
import adminUser from "./modules/admin/user.js";
import adminRole from "./modules/admin/role.js";
import adminSchool from "./modules/admin/school.js";
import home from "./modules/home.js";
export default createStore({
    namespaced: true,
    modules: {
        meta,
        app,
        account,
        alert,
        home,
        dashboard,
        adminSetting,
        adminSet,
        adminFolder,
        adminUser,
        adminRole,
        adminSchool
    },
});
