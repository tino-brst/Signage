import Vue from 'vue';
import VueSocketio from 'vue-socket.io';
import Screen from './Screen';

// Coneccion a webSockets
Vue.use(VueSocketio, 'http://localhost:4000');

new Vue({
	el: '#app',
	render: h => h(Screen)
})