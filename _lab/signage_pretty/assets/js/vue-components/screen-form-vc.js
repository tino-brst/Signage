var ScreenFormVC = {
	template: `
		<div class="screen-form-vc vue-component">
			<b-modal :active="active" v-on:close="close">
				<div class="modal-card">
					<form id="new-screen" v-on:submit.prevent="submit">
						<header class="modal-card-head">
							<p class="modal-card-title"> New Screen </p>
						</header>
						<section class="modal-card-body">
							<b-field label="Pin">
								<b-input
									type="text"
									v-model="pin"
									placeholder=" · · · · "
									v-on:blur="validatePin"
									required
									>
								</b-input>
							</b-field>
							<b-field label="Name">
								<b-input
									type="text"
									v-model="name"
									placeholder="enter a name for the screen"
									required
									>
								</b-input>
							</b-field>
							<b-field label="Content">
								<b-select placeholder="Select content" v-model="playlist" required>
									<option v-for="playlist in playlists" :value="playlist.id"> {{playlist.name}} </option>
								</b-select>
							</b-field>
						</section>
						<footer class="modal-card-foot">
							<input class="button is-primary" type="submit" form="new-screen" :disabled="!validPin" value="Done"/>
							<button class="button" type="button" v-on:click="close"> Cancel </button>
						</footer>
					</form>
				</div>
			</b-modal>
		</div>
	`,
	props: ["active"],
	data() {
		return {
			validPin: false,
			pin: "",
			name: "",
			playlist: "",
			setup: {}
		}
	},
	computed: {
		// Vuex State
		...Vuex.mapState(['currentGroup', 'playlists'])
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
					this.close();
				})
				.catch(error => {

				});
		},
		close() {
			this.pin = "";
			this.name = "";
			this.playlist = "";
			this.$emit('close');
		},
		// Vuex actions
		...Vuex.mapActions(['addScreen'])
	}
}