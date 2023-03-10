import { createRouter, createWebHistory } from "vue-router";

import index from '../user/index.vue';
import calendar from '../user/calendar.vue';
import upload from '../user/upload.vue';
import calendarv1 from '../user/calendarv1.vue';

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