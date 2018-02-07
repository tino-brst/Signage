<template>
	<div class="vue-component">
		<h4> Screen Form </h4>
		<form @submit.prevent="submit">
			<p>
				<label> pin </label>
				<input 
					type="text" 
					v-model="pin" 
					@blur="validatePin">
			</p>
			<p>
				<label> name </label>
				<input 
					type="text" 
					v-model="name">
			</p>
			<p>
				<label> content </label> 
				<select v-model="playlist">
					<option 
						v-for="playlist in playlists" 
						:value="playlist.id"
						:key="playlist.id">
						{{ playlist.name }}
					</option> 
				</select>
			</p>
			<p>	
				<input 
					type="submit"
					value="done"
					:disabled="!validPin">
				<input 
					type="button"
					value="cancel"
					@click="$emit('hide')"> 		
			</p>
		</form>
	</div>
</template>

<script>
import Vuex from 'vuex';
import axios from 'axios';

export default {
	data() {
		return {
			pin: "",
			validPin: false,
			udid: "",
			name: "",
			playlist: "",
			setup: {}
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
			axios.get(API_URL + 'setup', {params: {pin: this.pin}})
				.then(response => {
					this.validPin = true;
					this.setup = response.data;
				})
				.catch(error => {
					this.validPin = false;
				});
		},
		submit() {
			var screen = {
				name: this.name,
				parentId: this.currentGroup.id,
				extraFields: {
					playlist_id: this.playlist,
					udid: this.setup.udid				}
			}
			this.addScreen(screen)
				.then(response => {
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