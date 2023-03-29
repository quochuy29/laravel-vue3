import { createRouter, createWebHistory } from "vue-router";

import index from '../calendar/index.vue';
import calendar from '../calendar/calendar.vue';
import myRequest from '../request/index.vue';

const routes = [
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
export default createRouter({
	history: createWebHistory(),
	routes
})