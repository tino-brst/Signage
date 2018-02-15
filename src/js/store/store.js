import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

var inProduction = process.env.NODE_ENV === 'production';

export default new Vuex.Store({
	strict: !inProduction,
	state: {
		currentGroup: {
			id: '',
			name: '',
			parent_id: '',
			path: [],
			content: []
		},
		selectedItemId: null,
		// ----
		playlists: []
	},
	getters: {
		groups: state => {
			return state.currentGroup.content.filter(item => item.type == 'group');
		},
		screens: state => {
			return state.currentGroup.content.filter(item => item.type == 'screen');
		},
		selectedItem: state => {
			var index = state.currentGroup.content.findIndex(item => { return item.id === state.selectedItemId });
			return state.selectedItemId != null ? state.currentGroup.content[index] : null;
		}
	},
	mutations: {
		setCurrentGroup(state, group) {
			state.currentGroup = group;
			// el cambio de grupo limpia la seleccion
			state.selectedItemId = null;
		},
		setSelectedItem(state, id) {
			state.selectedItemId = id;
		},
		updateSelectedItem(state, updatedItem) {
			var index = state.currentGroup.content.findIndex(item => { return item.id === state.selectedItemId });
			Vue.set(state.currentGroup.content, index, updatedItem);
		},
		resetSelectedItem(state) {
			state.selectedItemId = null;
		},
		pushItem(state, item) {
			state.currentGroup.content.push(item);
		},
		removeItem(state, id) {
			var index = state.currentGroup.content.findIndex(item => { return item.id === id});
			state.currentGroup.content.splice(index, 1);
			// si el item eliminado es el seleccionado limpio la seleccion
			if (id === state.selectedItemId) {
				state.selectedItemId = null;
			}
		},
		// ----
		setPlaylists(state, playlists) {
			state.playlists = playlists;
		}
	},
	actions: {
		// Grupos
		loadRoot({commit}) {
			return new Promise((resolve, reject) => {
				axios.get(NEW_API_URL + 'groups', {params: {
					id: '',
					includePath: true,
					includeContent: true
				}})
					.then(response => {
						commit('setCurrentGroup', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		loadGroup({commit}, id) {
			return new Promise((resolve, reject) => {
				axios.get(NEW_API_URL + 'groups', {params: {
					id: id,
					includePath: true,
					includeContent: true
				}})
					.then(response => {
						commit('setCurrentGroup', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		addGroup({commit}, item) {
			return new Promise((resolve, reject) => {
				axios.put(NEW_API_URL + 'groups', item)
					.then(response => {
						commit('pushItem', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		updateSelectedGroup({commit}, updatedGroup) {
			return new Promise((resolve, reject) => {
				axios.post(NEW_API_URL + 'groups', updatedGroup)
					.then(response => {
						commit('updateSelectedItem', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		deleteGroup({commit}, id) {
			return new Promise((resolve, reject) => {
				axios.delete(NEW_API_URL + 'groups', {params: {id: id}})
					.then(response => {
						commit('removeItem', id);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		// Pantallas
		addScreen({commit}, item) {
			return new Promise((resolve, reject) => {
				axios.put(NEW_API_URL + 'screens', item)
					.then(response => {
						commit('pushItem', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		updateSelectedScreen({commit}, updatedScreen) {
			return new Promise((resolve, reject) => {
				axios.post(NEW_API_URL + 'screens', updatedScreen)
					.then(response => {
						commit('updateSelectedItem', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		deleteScreen({commit}, id) {
			return new Promise((resolve, reject) => {
				axios.delete(NEW_API_URL + 'screens', {params: {id: id}})
					.then(response => {
						commit('removeItem', id);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		// Playlists
		loadPlaylists({commit}) {
			return new Promise((resolve, reject) => {
				axios.get(NEW_API_URL + 'playlists')
					.then(response => {
						commit('setPlaylists', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
	}
});