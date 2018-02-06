var GroupOptionsVC = {
	template: `
		<div class="group-options-vc vue-component">
			<h5> Group options · {{newValues.name}} </h5>
			<form @submit.prevent="save">
					<label>
					  	name:
					  	<input type="text" v-model="newValues.name" placeholder="new name"/>
					</label>
				<button type="submit"> save </button>
				<button type="button" v-on:click="cancel"> cancel </button>
			</form>
		</div>
	`,
	props: ["group"],
	data() {
		return {
			// guardo una copia del item seleccionado para no editarlo directamente
			// (los "props" solo se acceden, no se deben modificar)
			newValues: Object.assign({}, this.group)
		}
	},
	methods: {
		save() {
			var updatedGroup = {
				id: this.group.id,
				name: this.newValues.name,
				extraFields: {}
			}
			this.updateSelectedGroup(updatedGroup);
		},
		cancel() {
			// restauro el valor inicialmente asignado
			this.newValues = Object.assign({}, this.group);
		},
		// Vuex actions
		...Vuex.mapActions(['updateSelectedGroup'])
	},
	watch: {
		group(newSelectedGroup) {
			this.newValues = Object.assign({}, newSelectedGroup);
		}
	}
}