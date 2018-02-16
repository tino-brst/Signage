<template>
	<div class="vue-component">

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
import Vuex from 'vuex';
import Navbar from '../components/Navbar';
import Content from '../components/Content';
import OptionsEditor from '../components/OptionsEditor';
import FormGroup from '../components/FormGroup';
import FormScreen from '../components/FormScreen';


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