var ScreenFormVC = {
	template: `
		<div class="screen-form-vc vue-component">
			<h4> Screen Form </h4>
			<form @submit.prevent="submit">
					<label>
					  	pin:
					  	<input type="text" v-model="pin" v-on:blur="validatePin"/>
					</label>
					<label>
					  	name:
					  	<input type="text" v-model="name"/>
					</label>
					<label>
					  	content:
  						<select v-model="playlist">
							<option v-for="playlist in playlists" :value="playlist.id"> {{playlist.name}} </option> 
						</select>
					</label>
				<button type="submit" :disabled="!validPin"> done </button>
				<button type="button" v-on:click="$emit('cancel')"> cancel </button>
			</form>
		</div>
	`,
	data() {
		return {
			pin: "",
			validPin: false,
			udid: "",
			name: "",
			playlist: "",
			setup: {}
		}
	},
	computed: {
		// Vuex State
		...Vuex.mapState(['currentGroup', 'playlists'])
	},
	created() {
		// le asigno una playlists por defecto (para que no quede en blanco)
		this.playlist = this.playlists[0].id;
	},
	methods: {
		validatePin() {
			axios.get(apiUrl + 'setup', {params: {pin: this.pin}})
					.then(response => {
						this.validPin = true;
						this.setup = response.data;
					})
					.catch(error => {
						this.validPin = false;
					});
		},
		submit() {
			var screen = {
				name: this.name,
				parentId: this.currentGroup.id,
				extraFields: {
					playlist_id: this.playlist,
					udid: this.setup.udid				}
			}
			this.addScreen(screen)
				.then(response => {
					// oculto el menu
					this.$emit('done');
				})
				.catch(error => {

				});
		},
		// Vuex actions
		...Vuex.mapActions(['addScreen'])
	}
}