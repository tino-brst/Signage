<template>
	<div class="vue-component">
		<h4> New Screen </h4>
		<form 
			id="new-screen" 
			@submit.prevent="submit">
			<p>
				<label> pin </label>
				<input 
					v-model="pin"
					type="text"  
					@blur="validatePin">
			</p>
			<p>
				<label> name </label>
				<input 
					v-model="name"
					type="text">
			</p>
			<p>
				<label> playlist </label> 
				<select v-model="playlist">
					<option 
						v-for="playlist in playlists" 
						:value="playlist.id"
						:key="playlist.id">
						{{ playlist.name }}
					</option> 
				</select>
			</p>
		</form>
		<p>	
			<input
				:disabled="!isValidPin"
				type="submit"
				value="done"
				form="new-screen">
			<input 
				type="button"
				value="cancel"
				@click="$emit('hide')"> 		
		</p>
	</div>
</template>

<script>
import Vuex from 'vuex';
import axios from 'axios';

export default {
	data() {
		return {
			pin: "",
			name: "",
			playlist: "",
			udid: "",
			isValidPin: false,
		}
	},
	computed: {
		// Vuex State
		...Vuex.mapState(['currentGroup', 'playlists'])
	},
	created() {
		// le asigno una playlists por defecto (para que no quede en blanco)
		this.playlist = this.playlists[0].id;
	},
	methods: {
		validatePin() {
			// obtengo el UDID de la pantalla a partir del pin ingresado
			this.$socket.emit('screenUdid', this.pin, (udid) => {
				if (udid) {
					this.isValidPin = true;
					this.udid = udid;
				} else {
					this.isValidPin = false;
				}
			})
		},
		submit() {
			var screen = {
				name: this.name,
				parentId: this.currentGroup.id,
				extraFields: {
					playlist_id: this.playlist,
					udid: this.udid
				}
			}
			this.addScreen(screen)
				.then(response => {
					var newScreen = response.data;
					// anuncio que se agrego la pantalla
					this.$socket.emit('screenAdded', newScreen.udid);
					// oculto el menu
					this.$emit('hide');
				})
				.catch(error => {

				});
		},
		// Vuex actions
		...Vuex.mapActions(['addScreen'])
	}
}
</script>