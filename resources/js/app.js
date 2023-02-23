import './bootstrap';
import {createApp} from 'vue';
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/antd.css';

import App from './fronts/App.vue';
import Router from './fronts/routes/Router';

const app = createApp(App).use(Router).use(Antd).mount("#app");