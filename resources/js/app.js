import './bootstrap';
import { createApp } from "vue";
import App from "./App.vue";
import store from "./helpers/store/index.js";
import router from "./helpers/router/index.js";
import "../css/app.css";
import VueTilt from "vue-tilt.js";
import VueSweetalert2 from "vue-sweetalert2";
import ArgonDashboard from "./argon-dashboard";

const appInstance = createApp(App);
appInstance.use(store);
appInstance.use(router);
appInstance.use(VueTilt);
appInstance.use(VueSweetalert2);
appInstance.use(ArgonDashboard);
appInstance.mount("#app");
