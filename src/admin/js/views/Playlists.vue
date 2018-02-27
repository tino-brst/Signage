<template>
	<div class="vue-component">

		<h2> Playlists </h2>

		<p>
			<button @click="showForm = true"> + new playlist </button>
		</p>

		<PlaylistsForm
			v-if="showForm"
			@hide="showForm = false"/>

		<hr>

		<div 
			v-if="playlists.length > 0"
			id="playlists">
			<PlaylistsItem
				v-for="playlist in playlists" 
				:playlist="playlist"
				:key="playlist.id"/>
		</div>
		<div v-else>
			<h4> No playlists created yet ... </h4>
		</div>

		<hr> 

		<PlaylistsOptionsEditor/>

	</div>
</template>

<script>
import Vuex from 'vuex';
import PlaylistsItem from '../components/PlaylistsItem';
import PlaylistsForm from '../components/PlaylistsForm';
import PlaylistsOptionsEditor from '../components/PlaylistsOptionsEditor';
import ImagesItem from '../components/ImagesItem'

export default {
	components: {
		PlaylistsItem,
		PlaylistsForm,
		PlaylistsOptionsEditor,
		ImagesItem
	},
	data() {
		return {
			showForm: false,
			showEditor: true
		}
	},
	computed: {
		// Vuex state
		...Vuex.mapState(['playlists', 'images'])
	},
	created() {
		this.loadPlaylists();
	},
	methods: {
		// Vuex actions
		...Vuex.mapActions(['loadPlaylists'])
	}
}
</script>

<style>
	#playlists > .vue-component:hover {
		background-color: #b9b9b966;
	}
</style>