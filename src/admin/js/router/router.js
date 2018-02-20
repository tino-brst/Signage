import Vue from 'vue';
import VueRouter from 'vue-router';
import GroupsAndScreens from '../views/GroupsAndScreens';
import Playlists from '../views/Playlists';
import Images from '../views/Images';

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
	},
	{
		path: '/images',
		component: Images
	}
]

export default new VueRouter({
	routes
})