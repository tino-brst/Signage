var GroupFormVC = {
	template: `
		<div class="group-form-vc vue-component">
			<h4> Group Form </h4>
			<form @submit.prevent="submit">
					<label>
					  	name:
					  	<input type="text" v-model="name"/>
					</label>
				<button type="submit"> done </button>
				<button type="button" v-on:click="$emit('cancel')"> cancel </button>
			</form>
		</div>
	`,
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
					// oculto el menu
					this.$emit('done');
				})
				.catch(error => {

				});
		},
		// Vuex actions
		...Vuex.mapActions(['addGroup'])
	}
}