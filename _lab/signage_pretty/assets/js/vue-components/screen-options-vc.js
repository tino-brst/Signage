var ScreenOptionsVC = {
	template: `
		<div class="screen-options-vc vue-component">
			<h5> Screen options · {{newValues.name}} </h5>
			<form @submit.prevent="save">
					<label>
					  	name:
					  	<input type="text" v-model="newValues.name" placeholder="new name"/>
					</label>
					<label>
					  	content:
  						<select v-model="newValues.playlist_id">
							<option v-for="playlist in playlists" :value="playlist.id"> {{playlist.name}} </option> 
						</select>
					</label>
				<button type="submit"> save </button>
				<button type="button" v-on:click="cancel"> cancel </button>
			</form>
		</div>
	`,
	props: ["screen"],
	data() {
		return {
			// guardo una copia del item seleccionado para no editarlo directamente
			// e implementar "ctrl-z" al apretar "cancelar"
			// (los "props" solo se acceden, no se deben modificar)
			newValues: Object.assign({}, this.screen)
		}
	},
	computed: {
		// Vuex state
		...Vuex.mapState(['playlists'])
	},
	methods: {
		save() {
			var updatedScreen = {
				id: this.screen.id,
				name: this.newValues.name,
				extraFields: {
					playlist_id: this.newValues.playlist_id
				}
			}
			this.updateSelectedScreen(updatedScreen);
		},
		cancel() {
			// restauro el valor inicialmente asignado
			this.newValues = Object.assign({}, this.screen);
		},
		// Vuex actions
		...Vuex.mapActions(['updateSelectedScreen'])
	},
	watch: {
		screen(newSelectedScreen) {
			this.newValues = Object.assign({}, newSelectedScreen);
		}
	}
}