<template>
	<div class="vue-component">
		<h4> Editor </h4>
		<Draggable 
			id="playlistItems"
			:list="items">
			<div 
				class="playlistItem"
				v-for="item in items"
				:key="item.id">
				<img :src="item.location">
			</div>
		</Draggable>
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
	data() {
		return {
			playlist: {},
			items: []
		}
	},
	computed: {
		// Vuex state
		...Vuex.mapState(['playlists'])
	},
	watch: {
		playlists() {
			this.playlist = this.playlists[0];
			// cargo items
			axios.get(API_URL + 'playlists', {params: {
				id: this.playlist.id,
				includeItems: true
			}})
				.then(response => {
					this.items = response.data.items;
				})
				.catch(error => {
					
				})
		}
	},
	created() {
		this.loadImages();
	},
	methods: {
		// Vuex actions
		...Vuex.mapActions(['loadImages'])
	}
}
</script>

<style>
	#playlistItems {
		display: flex;
		margin-top: 1rem;
	}
	#playlistItems img {
		height: 7rem;
		object-fit: cover;
		border-radius: 0.5rem;
	}
	.playlistItem {
		display: flex;
		background-color: #00000000;
		margin-right:1rem;
		overflow: hidden;
	}
	.playlistItem[Attributes Style] {
    	webkit-user-drag: element;
   	}
</style>
