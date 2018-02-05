import Vue from 'vue'
import Vuex from 'vuex'
import App from './AdminApp.vue'

Vue.use(Vuex);

new Vue({
	el: '#app',
	render: h => h(App)
})