<template>
	<div class="vue-component">
		<h4> {{ screen.name }} </h4>
		<form @submit.prevent="save">
			<p>
				<label> name </label>
				<input 
					type="text" 
					v-model="newValues.name" 
					placeholder="new name">
			</p>
			<p>
				<label> content </label>
				<select v-model="newValues.playlist_id">
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
					value="save">
				<input 
					type="button" 
					value="cancel" 
					@click="cancel">
			</p>
		</form>
	</div>
</template>

<script>
import Vuex from 'vuex';

export default {
	props: {
		screen: {
			type: Object,
			default: () => { return {} }
		}
	},
	data() {
		return {
			// guardo una copia del item seleccionado para no editarlo directamente
			// e implementar "ctrl-z" al apretar "cancelar"
			// (los "props" solo se acceden, no se deben modificar)
			newValues: Object.assign({}, this.screen)
		}
	},
	computed: {
		// Vuex state
		...Vuex.mapState(['playlists'])
	},
	watch: {
		screen(newSelectedScreen) {
			this.newValues = Object.assign({}, newSelectedScreen);
		}
	},
	methods: {
		save() {
			var updatedScreen = {
				id: this.screen.id,
				name: this.newValues.name,
				extraFields: {
					playlist_id: this.newValues.playlist_id
				}
			}
			this.updateSelectedScreen(updatedScreen);
		},
		cancel() {
			// restauro el valor inicialmente asignado
			this.newValues = Object.assign({}, this.screen);
		},
		// Vuex actions
		...Vuex.mapActions(['updateSelectedScreen'])
	}
}
</script>