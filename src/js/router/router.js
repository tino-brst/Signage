import Vue from 'vue';
import VueRouter from 'vue-router';
import GroupsAndScreens from '../views/GroupsAndScreens';
import Playlists from '../views/Playlists';

Vue.use(VueRouter);

var routes = [
	{
		path: '/',
		redirect:'/groups-and-screens'
	},
	{
		path: '/groups-and-screens',
		component: GroupsAndScreens
	},
	{
		path: '/playlists',
		component: Playlists
	}
]

export default new VueRouter({
	routes
})