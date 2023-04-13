import { createStore } from "vuex";
import config from "./modules/config";
import user from "./modules/user";
import adminDashboard from "./modules/admin/dashboard";
import adminSettings from "./modules/admin/setting";
import adminNotification from "./modules/admin/notification";
import adminUser from "./modules/admin/user";
import adminRole from "./modules/admin/role";
import adminSchool from "./modules/admin/school";
import adminLesson from "./modules/admin/lesson";
import adminCourse from "./modules/admin/course";
import home from "./modules/home/home";
import schoolDashboard from "./modules/school/dashboard";
import school from "./modules/school/school";
import schoolUser from "./modules/school/user";
import lesson from "./modules/home/lesson";
import learn from "./modules/home/learn";

const store = createStore({
    modules: {
        config,
        user,
        home,
        lesson,
        learn,
        adminDashboard,
        adminSettings,
        adminNotification,
        adminUser,
        adminRole,
        adminSchool,
        adminLesson,
        adminCourse,
        school,
        schoolDashboard,
        schoolUser,
    },
});

export default store;
