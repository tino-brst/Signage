import Vue from 'vue';
import store from './store/store';
import router from './router/router';
import VueSocketio from 'vue-socket.io';
import Admin from './Admin';

// Coneccion a webSockets
Vue.use(VueSocketio, 'http://localhost:4000');

new Vue({
	el: '#app',
	store,
	router,
	render: h => h(Admin)
})