import Vue from 'vue';
import store from './store/store';
import router from './router/router';
import Admin from './Admin';

new Vue({
	el: '#app',
	store,
	router,
	render: h => h(Admin)
})