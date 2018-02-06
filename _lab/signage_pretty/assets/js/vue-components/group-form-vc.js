var GroupFormVC = {
	template: `
		<div class="group-form-vc vue-component">
			<b-modal :active="active" v-on:close="close">
				<div class="modal-card">
					<form id="new-group" v-on:submit.prevent="submit">
						<header class="modal-card-head">
							<p class="modal-card-title"> New Group </p>
						</header>
						<section class="modal-card-body">
							<b-field label="Name">
								<b-input
									type="text"
									v-model="name"
									placeholder="enter a name for the group"
									required
									>
								</b-input>
							</b-field>
						</section>
						<footer class="modal-card-foot">
							<input class="button is-primary" type="submit" form="new-group" value="Done"/>
							<input class="button" type="button" v-on:click="close" value="Cancel"/> 
						</footer>
					</form>
				</div>
			</b-modal>
		</div>
	`,
	props: ["active"],
	data() {
		return {
			name: ""
		}
	},
	computed: {
		// Vuex state
		...Vuex.mapState(['currentGroup'])
	},
	methods: {
		submit() {
			var group = {
				name: this.name,
				parentId: this.currentGroup.id,
				extraFields: {}
			}
			this.addGroup(group)
				.then(response => {
					this.close();
				})
				.catch(error => {

				});
		},
		close() {
			this.name = "";
			this.$emit('close');
		},
		// Vuex actions
		...Vuex.mapActions(['addGroup'])
	}
}