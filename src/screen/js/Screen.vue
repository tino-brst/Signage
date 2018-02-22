<template>
	<div id="app">
		<div v-if="showSetup">
			<h1> Setup Pin - {{ setupPin }} </h1>
		</div>
		<div
			v-else
			id="content">
			<img
				v-for="(item, index) in playlist.items"
				:key="index"
				:src="item.location">
		</div>
	</div>
</template>

<script>
import axios from 'axios';

// CAMBIAR ID A UDID

export default {
	data() {
		return {
			screen: {},
			playlist: {
				items: []
			},
			showSetup: false,
			setupPin: {}
		}
	},
	created() {
		this.$socket.emit('screenConnected', udid);
		this.updateScreen();
	},
	methods: {
		updateScreen() {
			// veo si la pantalla ya es parte del sistema
			axios.get(API_URL + 'screens', {params: {
				udid: udid
			}})
				.then(response => {
					// si ya es parte del sistema, cargo su contenido
					this.screen = response.data;
					this.loadContent();
				})
				.catch(error => {
					// si no, inicio el modo setup (muestra pin, etc)
					this.startSetup();
				});
		},
		startSetup() {
			// ...emit(eventName, sentData, callbackFunction)
			// callbackFunction <- su ejecucion se inicia desde el servidor 
			this.$socket.emit('screenSetup', udid, (pin) => {
				// presento pantalla de setup
				this.setupPin = pin;
				this.showSetup = true;
			});
		},
		loadContent() {
			// obtengo contenido de la pantalla
			axios.get(API_URL + 'playlists', {params: {
				id: this.screen.playlist_id,
				includeItems: true
			}})
				.then(response => {
					this.playlist = response.data;
					this.showSetup = false;
				})
				.catch(error => {

				});
		}
	},
	sockets: {
		updateScreen() {
			this.updateScreen();
		},
		playlistUpdated(playlistId) {
			if (playlistId === this.screen.playlist_id) {
				this.loadContent();
			}
		}
	}
}
</script>

<style>
	html {
		font-family: sans-serif;
		background-color: whitesmoke;
	}
	#content {
		display: flex;
		flex-wrap: wrap;
	}
	img {
		height: 10rem;
		object-fit: cover;
		transition: 1s;
		margin-right: 1rem;
		margin-bottom: 1rem;
		border-radius: 0.5rem;
	}
</style>
