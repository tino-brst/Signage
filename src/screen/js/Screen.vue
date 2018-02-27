<template>
	<div id="app">

		<transition name="setup">
			<div
				v-if="showSetup"
				id="setup">
				<div id="pin">
					<h4> pin de configuración </h4>
					<h1> {{ formattedSetupPin }} </h1>
				</div>
				<div id="brand">
					<h4> Signage </h4>
					<img src="public/assets/images/coop_logo.png">
				</div>
				<div id="footer">
					<img src="public/assets/images/footer.png">
				</div>
			</div>
		</transition>

		<div
			v-if="!showSetup"
			id="content"
			@click="nextItem">
			<transition-group name="items-slide">
				<img
					v-for="(item, index) in playlist.items"
					v-show="index === currentItemIndex"
					:key="index"
					:src="item.location">
			</transition-group>
		</div>

	</div>
</template>

<script>
import axios from 'axios';

export default {
	data() {
		return {
			screen: {},
			playlist: {},
			currentItemIndex: 0,
			showSetup: false,
			setupPin: ''
		}
	},	
	computed: {
		formattedSetupPin() {
			return this.setupPin.split('').join(' ');
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
					this.currentItemIndex = 0;
				})
				.catch(error => {

				});
		},
		nextItem() {
			this.currentItemIndex = (this.currentItemIndex + 1) % this.playlist.items.length;
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
	html, body, #app {		
		height: 100%;
		width: 100%;
		margin: 0;
		padding: 0;
		overflow: hidden;
		font-family: Avenir;
		background-color: black;
	}
	#setup {
		height: 100%;
		color: lightgray;
		background-color: #212121;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
	#pin {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
	}
	#pin h4 {
		opacity: 0.5;
		font-weight: lighter;
		font-size: 3rem;
		margin: 0;
	}
	#pin h1 {
		font-size: 10rem;
		margin: 0;
		color: white;
	}

	#brand {
		position: absolute;
		width: 100%;
		bottom: 0;
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: flex-end;
	}
	#brand h4 {
		font-size: 3rem;
		font-weight: lighter;
		margin-top: 0;
		margin-right: 1rem;
		margin-bottom: 2rem;
		opacity: 0.9;
	}
	#brand img {
		height: 3rem;
		opacity: 0.3;
		margin-right: 1.5rem;
		margin-bottom: 1.4rem;
	}

	#footer {
		position: absolute;
		height: 1rem;
		width: 100%;
		bottom: 0;
	}
	#footer img {
		height: 1rem;
		width: 100%;
	}
	#content img {
		position: absolute;
		height: 100%;
		width: 100%;
		object-fit: cover;
	}
	.items-slide-enter-active, .items-slide-leave-active {
		transition: all 3s;
	}
	.items-slide-enter {
		transform: translateY(100%);
		opacity:  1;
	}
	.items-slide-enter-to {
		transform: translateY(0%);
		z-index: 2;
		opacity: 1;
	}
	.items-slide-leave {
		z-index:  1;
	}
	.items-slide-leave-to {
		transform: translateY(-80%);
		opacity: 0;
		z-index: 1;
	}

	.setup-enter-active, .setup-leave-active {
		transition: all 1s;
	}
	.setup-enter {
		opacity:  0;
	}
	.setup-enter-to {
		opacity: 1;
	}
	.setup-leave-to {
		opacity: 0;
	}
</style>
