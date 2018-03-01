import "babel-polyfill";
import Vue from 'vue';
import VueSocketio from 'vue-socket.io';
import Screen from './Screen';

// Coneccion a webSockets (cambiar a '10.0.2.2' en caso de usar Android Emulator)
Vue.use(VueSocketio, 'http://10.0.2.2:4000');

new Vue({
	el: '#app',
	render: h => h(Screen)
})