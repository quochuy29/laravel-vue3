import { createRouter, createWebHistory } from "vue-router";

import calendar from '../calendar/calendar.vue';
import myRequest from '../request/index.vue';
import myHistory from '../leave-history/index.vue';
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
				path: '/calendar',
				name: 'calendar',
				component: calendar
			},
			{
				path: '/my-request',
				name: 'my-request',
				component: myRequest
			},
			{
				path: '/my-history',
				name: 'my-history',
				component: myHistory
			}
		]
	}
]
export default createRouter({
	history: createWebHistory(),
	routes
})