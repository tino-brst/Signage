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
			:list="newValues.items"
			:options="{group: {name: 'items', pull: false, put: true}}">
			<div 
				class="playlistItem"
				v-for="(item, index) in newValues.items"
				:key="index">
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
			this.updateSelectedPlaylist(updatedPlaylist)
				.then( response => {
					// anuncio cambio en la playlist
					this.$socket.emit('playlistUpdated', response.data.id);
				});
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
		min-height: 5rem;
	}
	#playlistItems img {
		height: 5rem;
		object-fit: cover;
		border-radius: 0.5rem;
	}
	.playlistItem {
		display: flex;
		padding: 0.5rem;
	}
	.sortable-ghost {
		opacity:  0.5;
	}
	.sortable-chosen {
		background-color: #ffffff00;
	}
	.sortable-chosen>img{
		border-style: solid;
		border-width: 2px;
		border-color: white;
	}
</style>