import Vue from 'vue';
import store from './store/store';
import AdminVue from './AdminVue';

new Vue({
	el: '#app',
	store,
	render: h => h(AdminVue)
})