<template>
	<div class="vue-component">
		<h4> New Playlist </h4>
		<form 
			id="new-playlist"
			@submit.prevent="submit">
			<p>
				<label for="name"> name </label>
				<input
					v-model="name"
					type="text">
			</p>
		</form>
		<p>
			<input 
				type="submit" 
				value="done"
				form="new-playlist">
			<input 
				type="button"
				value="cancel"
				@click="$emit('hide')">
		</p>
	</div>
</template>

<script>
import Vuex from 'vuex';

export default {
	data() {
		return {
			name: ""
		}
	},
	methods: {
		submit() {
			var playlist = {
				name: this.name
			}
			this.addPlaylist(playlist)
				.then(response => {
					// oculto el menu
					this.$emit('hide');
				})
				.catch(error => {

				});
		},
		// Vuex actions
		...Vuex.mapActions(['addPlaylist'])
	}
}
</script>