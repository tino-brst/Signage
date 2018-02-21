<template>
	<div class="vue-component">
		<h4> {{ playlist.name }} </h4>
		<form 
			id="playlist-options"
			@submit.prevent="save">
			<p>
				<label> name </label>
				<input 
					type="text" 
					v-model="newValues.name" 
					placeholder="new name">
			</p>
		</form>

		<hr>

		<label> items </label>
		<Draggable 
			id="playlistItems"
			:list="newValues.items">
			<div 
				class="playlistItem"
				v-for="item in newValues.items"
				:key="item.id">
				<img :src="item.location">
			</div>
		</Draggable>

		<p>
			<input 
				type="submit" 
				form="playlist-options"
				value="save">
			<input
				type="button" 
				value="cancel"
				@click="cancel">
		</p>
	</div>
</template>

<script>
import Vuex from 'vuex';
import axios from 'axios';
import Draggable from 'vuedraggable';

export default {
	components: {
		Draggable
	},
	props: {
		playlist: {
			type: Object,
			default: () => { return {} }
		}
	},
	data() {
		return {
			// guardo una copia del item seleccionado para no editarlo directamente
			// (los "props" solo se deberian acceder, no se deben modificar)
			newValues: JSON.parse(JSON.stringify(this.playlist))
		}
	},
	watch: {
		playlist(newSelectedPlaylist) {
			this.newValues = JSON.parse(JSON.stringify(newSelectedPlaylist));
		}
	},
	methods: {
		save() {
			var updatedPlaylist = {
				id: this.playlist.id,
				name: this.newValues.name,
				items: this.newValues.items
			}
			this.updateSelectedPlaylist(updatedPlaylist);
		},
		cancel() {
			// restauro el valor inicialmente asignado
			this.newValues = JSON.parse(JSON.stringify(this.playlist));
		},
		// Vuex actions
		...Vuex.mapActions(['updateSelectedPlaylist'])
	}
}
</script>

<style>
	#playlistItems {
		display: flex;
		margin-top: 0.5rem;
		margin-bottom: 0.5rem;
	}
	#playlistItems img {
		height: 5rem;
		object-fit: cover;
		border-radius: 0.5rem;
	}
	.playlistItem {
		display: flex;
		margin-right:1rem;
	}
</style>