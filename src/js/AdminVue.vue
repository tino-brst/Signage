<template>
	<div id="app">

		<Navbar
			@add-screen="showFormScreen = true"
			@add-group="showFormGroup = true"/>
		<FormScreen 
			v-if="showFormScreen"
			@hide="hideFormScreen"/>
		<FormGroup 
			v-if="showFormGroup"
			@hide="hideFormGroup"/>

		<hr>

		<Content/>

		<hr>

		<OptionsEditor/>

	</div>
</template>

<script>
import axios from 'axios';
import Vuex from 'vuex';
import Navbar from './components/Navbar';
import Content from './components/Content';
import OptionsEditor from './components/OptionsEditor';
import FormGroup from './components/FormGroup';
import FormScreen from './components/FormScreen';


export default {
	components: {
		Navbar,
		Content,
		OptionsEditor,
		FormGroup,
		FormScreen
	},
	data() {
		return {
			showFormScreen: false,
			showFormGroup: false
		}
	},
	computed: {
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
		...Vuex.mapActions(['loadRoot', 'loadPlaylists'])
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
