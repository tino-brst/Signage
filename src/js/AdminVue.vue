<template>
	<div id="app">

		<h1> {{ currentGroup.name }} </h1>

		<hr>

		<button
			@click="loadGroup(currentGroup.parent_id)"
			:disabled="currentGroupIsRoot"> 
			&larr;
		</button>
		<button @click="showFormGroup = true"> + add group </button>
		<button @click="showFormScreen = true"> + add screen </button>

		<FormScreen 
			v-if="showFormScreen" 
			@hide="hideFormScreen"/>
		<FormGroup 
			v-if="showFormGroup" 
			@hide="hideFormGroup"/>

		<hr>

		<CurrentGroupPath :items="currentGroup.path"/>

		<hr>

		<CurrentGroupContent/>

		<hr>

		<OptionsEditor/>

	</div>
</template>

<script>
import axios from 'axios';
import Vuex from 'vuex';
import CurrentGroupPath from './components/CurrentGroupPath';
import CurrentGroupContent from './components/CurrentGroupContent';
import FormGroup from './components/FormGroup';
import FormScreen from './components/FormScreen';
import OptionsEditor from './components/OptionsEditor';


export default {
	components: {
		CurrentGroupPath,
		CurrentGroupContent,
		FormGroup,
		FormScreen,
		OptionsEditor
	},
	data() {
		return {
			showFormScreen: false,
			showFormGroup: false
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
		hideFormScreen() {
			this.showFormScreen = false;
		},
		hideFormGroup() {
			this.showFormGroup = false;
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
