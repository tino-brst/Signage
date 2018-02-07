<template>
	<div id="app">

		<h1> {{ currentGroup.name }} </h1>

		<hr>

		<button 
			@click="loadGroup(currentGroup.parent_id)"
			:disabled="currentGroupIsRoot"> 
			&larr;
		</button>
		<button @click="showGroupForm = true"> + add group </button>
		<button @click="showScreenForm = true"> + add screen </button>

		<ScreenFormVue 
			v-if="showScreenForm" 
			@hide="hideScreenForm"/>
		<GroupFormVue 
			v-if="showGroupForm" 
			@hide="hideGroupForm"/>

		<hr>

		<PathVue :items="currentGroup.path"/>

		<hr>

		<GroupContentVue/>

	</div>
</template>

<script>
import axios from 'axios';
import Vuex from 'vuex';
import PathVue from './components/PathVue';
import GroupFormVue from './components/GroupFormVue';
import ScreenFormVue from './components/ScreenFormVue';
import GroupContentVue from './components/GroupContentVue';

export default {
	name: 'Admin',
	components: {
		PathVue,
		GroupFormVue,
		ScreenFormVue,
		GroupContentVue
	},
	data() {
		return {
			showScreenForm: false,
			showGroupForm: false
		}
	},
	computed: {
		currentGroupIsRoot() {
			return this.currentGroup.path.length == 1;
		},
		// Vuex state - mapeo state del store (evito tener que accederlos via this.$store.state...) -
		...Vuex.mapState(['currentGroup'])
	},
	created() {
		// cargo el punto de partida de la jerarquia de grupos
		this.loadRoot();
		this.loadPlaylists();
	},
	methods: {
		// visibilidad de "formularios"
		hideScreenForm() {
			this.showScreenForm = false;
		},
		hideGroupForm() {
			this.showGroupForm = false;
		},
		// Vuex actions
		...Vuex.mapActions(['loadRoot', 'loadGroup', 'loadPlaylists'])
	}
}
</script>

<style>
	html {
		font-family: sans-serif;
		background-color: whitesmoke;
	}
	.vue-component {
		background: #ccc6;
		border-radius: 0.3rem;
		margin-top:	0.5rem;
		margin-bottom:	0.5rem;
		padding: 0.5rem; 
	}
	h4 {
		margin: 0;
	}
</style>
