import { createRouter, createWebHistory } from "vue-router";

import index from '../user/index.vue';
import calendar from '../user/calendar.vue';
import upload from '../user/upload.vue';

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
		path: '/upload',
		name: 'upload',
		component: upload
	}
]

export default createRouter({
	history: createWebHistory(),
	routes
})