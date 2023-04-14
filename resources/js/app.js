import './bootstrap';
import {createApp} from 'vue';
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/antd.css';

import App from './fronts/App.vue';
import Router from './fronts/routes/Router';
import load from '@/windowLoad/load';
import ProLayout, { PageContainer } from '@ant-design-vue/pro-layout';
import '@ant-design-vue/pro-layout/dist/style.css';

const app = createApp(App).use(ProLayout).use(PageContainer).use(Router).use(Antd);
function importCommonComponents() {
    let context = import.meta.globEager('./fronts/common/*.vue');
    for (const key of Object.keys(context)) {
        const module = context[key].default;
        const name = key.split('/').pop().split('.')[0];
      app.component(`Common-${name}`, module);
    }
  }
importCommonComponents();
const infor = JSON.parse(sessionStorage.getItem('inforUser'));
if (infor) {
    window.axios.defaults.headers.common["Authorization"] = `${infor.token_type} ${infor.access_token}`;
}
window.addEventListener('load', load, false);
app.mount("#app");

