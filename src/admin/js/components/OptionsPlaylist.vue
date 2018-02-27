<template>
	<div class="vue-component">

		<h4> {{ playlist.name }} </h4>

		<form 
			id="playlist-options"
			@submit.prevent="save">
			<p>
				<label> name </label>
				<input 
					v-model="newValues.name"
					type="text" 
					placeholder="new name">
			</p>
		</form>

		<hr>

		<label> items </label>
		<Draggable 
			id="playlistItems"
			:list="newValues.items"
			:options="{group: {name: 'items', pull: false, put: true}}">
			<PlaylistTimelineItem
				v-for="(item, index) in newValues.items"
				:key="index"
				:item="item"
				@delete="deleteTimelineItem(index)"/>
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
import PlaylistTimelineItem from './PlaylistTimelineItem';

export default {
	components: {
		Draggable,
		PlaylistTimelineItem
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
		deleteTimelineItem(index) {
			this.newValues.items.splice(index, 1);
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
		overflow: scroll;
		min-height: 5rem;
	}
	#playlistItems img {
		height: 5rem;
		object-fit: cover;
		border-radius: 0.5rem;
	}
</style>