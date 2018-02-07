import Vue from 'vue';
import store from './store/store';
import App from './Admin';

new Vue({
	el: '#app',
	store,
	render: h => h(App)
})