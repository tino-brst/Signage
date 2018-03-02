import "babel-polyfill";
import Vue from 'vue';
import Screen from './Screen';

new Vue({
	el: '#app',
	render: h => h(Screen)
})