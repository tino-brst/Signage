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

		playlists: [],
		selectedPlaylistId: null,

		images: []
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
		},
		selectedPlaylist: state => {
			var index = state.playlists.findIndex(item => { return item.id === state.selectedPlaylistId });
			return state.selectedPlaylistId != null ? state.playlists[index] : null;
		}
	},
	mutations: {
		// Grupos y Pantallas
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

		// Playlists
		setPlaylists(state, playlists) {
			state.playlists = playlists;
		},
		setSelectedPlaylist(state, id) {
			state.selectedPlaylistId = id;
		},
		pushPlaylist(state, playlist) {
			state.playlists.push(playlist);
		},
		removePlaylist(state, id) {
			var index = state.playlists.findIndex(item => { return item.id === id});
			state.playlists.splice(index, 1);
			// si el item eliminado es el seleccionado limpio la seleccion
			if (id === state.selectedPlaylistId) {
				state.selectedPlaylistId = null;
			}
		},

		// Imagenes
		setImages(state, images) {
			state.images = images
		},
		pushImages(state, images) {
			images.forEach((image) => {
				state.images.push(image);
			})
		},
		removeImage(state, id) {
			var index = state.images.findIndex(item => { return item.id === id});
			state.images.splice(index, 1);
		}
	},
	actions: {
		// Grupos
		loadRoot({commit}) {
			return new Promise((resolve, reject) => {
				axios.get(API_URL + 'groups', {params: {
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
				axios.get(API_URL + 'groups', {params: {
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
				axios.put(API_URL + 'groups', item)
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
				axios.post(API_URL + 'groups', updatedGroup)
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
				axios.delete(API_URL + 'groups', {params: {id: id}})
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
				axios.put(API_URL + 'screens', item)
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
				axios.post(API_URL + 'screens', updatedScreen)
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
				axios.delete(API_URL + 'screens', {params: {id: id}})
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
				axios.get(API_URL + 'playlists')
					.then(response => {
						commit('setPlaylists', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		addPlaylist({commit}, playlist) {
			return new Promise((resolve, reject) => {
				axios.put(API_URL + 'playlists', playlist)
					.then(response => {
						commit('pushPlaylist', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		deletePlaylist({commit}, id) {
			return new Promise((resolve, reject) => {
				axios.delete(API_URL + 'playlists', {params: {id: id}})
					.then(response => {
						commit('removePlaylist', id);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},

		// Imagenes
		loadImages({commit}) {
			return new Promise((resolve, reject) => {
				axios.get(API_URL + 'images')
					.then(response => {
						commit('setImages', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		addImages({commit}, images) {
			return new Promise((resolve, reject) => {
				axios.post(API_URL + 'images', images)
					.then(response => {
						commit('pushImages', response.data);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
		deleteImage({commit}, id) {
			return new Promise((resolve, reject) => {
				axios.delete(API_URL + 'images', {params: {id: id}})
					.then(response => {
						commit('removeImage', id);
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			});
		},
	}
});