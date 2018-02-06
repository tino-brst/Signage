import Vue from 'vue';
import store from './store/store';
import App from './components/Admin';

new Vue({
	el: '#app',
	store,
	render: h => h(App)
})