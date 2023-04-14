import { createRouter, createWebHistory } from "vue-router";

import index from '../calendar/index.vue';
import calendar from '../calendar/calendar.vue';
import myRequest from '../request/index.vue';
import login from '../auth/login.vue';
import main from '../layout/main.vue';

const routes = [
	{
		path: '/login',
		name: 'login',
		component: login
	},
	{
		path: '/',
		name: 'main',
		component: main,
		children: [
			{
				path: '/index',
				name: 'index',
				component: index
			},
			{
				path: '/calendar',
				name: 'calendar',
				component: calendar
			},
			{
				path: '/my-request',
				name: 'my-request',
				component: myRequest
			}
		]
	}
]
export default createRouter({
	history: createWebHistory(),
	routes
})