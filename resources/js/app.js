import './bootstrap';

import {createApp} from 'vue'

import app from '@/components/App.vue'
import VueLazyload from 'vue-lazyload';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import 'flowbite'

import router from '@/router'

createApp(app).use(router).use(VueLazyload).use(Toast).mount("#app")