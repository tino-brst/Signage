<template>
	<div 
		:class="{ selected: selected }"
		class="vue-component"
		@click.stop="setSelectedPlaylist(playlist.id)">
		<li>
			<span> {{ playlist.name }} </span>
			<button @click.stop="deletePlaylist(playlist.id)"> delete </button>
		</li>
	</div>
</template>

<script>
import Vuex from 'vuex';

export default {
	props: {
		playlist: {
			type: Object,
			default: () => { return {} }
		}
	},
	computed: {
		selected() {
			return this.playlist.id === this.selectedPlaylistId;
		},
		// Vuex state
		...Vuex.mapState(['selectedPlaylistId'])
	},
	methods: {
		// Vuex actions
		...Vuex.mapActions(['deletePlaylist']),
		...Vuex.mapMutations(['setSelectedPlaylist'])
	}
}
</script>

<style>

</style>
