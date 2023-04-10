import './bootstrap';
import '../css/app.css';

import { createApp } from "vue";
import OneSignalVuePlugin from '@onesignal/onesignal-vue3';
import VueGtag from "vue-gtag";
import { pageTitle } from 'vue-page-title';
import VueTilt from "vue-tilt.js";
import argon from "./helpers/other/argon";
import routers from './helpers/routes';
import stores from './helpers/stores';

import app_view from "./app.vue";

const app = createApp(app_view);
app.use(OneSignalVuePlugin, { appId: '1a3c6ac3-1e41-4338-bb97-99fb99ae5140', });
app.use(VueGtag, { config: { id: "G-8T9RFMHSRP" } });
app.use(pageTitle({ mixin: true, }));
app.use(VueTilt);
app.use(argon);
app.use(routers);
app.use(stores);
app.mount('#app');