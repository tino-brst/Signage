<template>
	<div id="app">
		<h1 v-if="showSetup"> Setup Pin: {{ setupPin }} </h1>
		<div 
			v-else
			id="content">
			<img
				v-for="item in content.items"
				:key="item.id"
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
			screen: {
				udid: id
			},
			content: {
				items: []
			},
			showSetup: false,
			setupPin: {}
		}
	},
	created() {
		// veo si la pantalla ya es parte del sistema
		axios.get(API_URL + 'screens', {params: {
			id: id
		}})
			.then(response => {
				// si ya es parte del sistema, cargo su contenido
				this.screen = response.data;
				this.loadContent();
			})
			.catch(error => {
				// si no, inicio el modo setup (muestra pin, etc)
				this.startSetup()
			});

	},
	methods: {
		startSetup() {
			axios.put(API_URL + 'setup', {udid: this.screen.udid})
				.then(response => {
					this.setupPin = response.data.setup.pin;
					this.showSetup = true;
				})
				.catch(error => {
					console.log(error.response.data.message);
				})
		},
		loadContent() {
			// obtengo contenido de la pantalla
			axios.get(API_URL + 'playlists', {params: {
				id: this.screen.playlist_id,
				includeItems: true
			}})
				.then(response => {
					// si ya es parte del sistema, muestro su contenido correspondiente
					this.content = response.data;
				})
				.catch(error => {

				});
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
	}
	img {
		height: 10rem;
		object-fit: cover;
		transition: 1s;
		margin-right: 1rem;
		border-radius: 0.5rem;
	}
</style>
