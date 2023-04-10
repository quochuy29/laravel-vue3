import './bootstrap';
import {createApp} from 'vue';
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/antd.css';

import App from './fronts/App.vue';
import Router from './fronts/routes/Router';  

const app = createApp(App).use(Router).use(Antd);
function importCommonComponents() {
    let context = import.meta.globEager('./fronts/common/*.vue');
    console.log(context);
    for (const key of Object.keys(context)) {
        const module = context[key].default;
        const name = key.split('/').pop().split('.')[0];
      app.component(`Common-${name}`, module);
    }
  }
importCommonComponents();
app.mount("#app");
