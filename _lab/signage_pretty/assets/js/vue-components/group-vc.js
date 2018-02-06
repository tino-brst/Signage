var GroupVC = {
	template: `
		<div class="group-vc vue-component box" v-on:click.stop="setSelectedItem(group.id)" :class="{selected: selected}">

			<div class="level">
				<div class="level-left">
					<div class="level-item">
						<p class="is-size-5 is-capitalized"> {{group.name}} </p>
					</div>
				</div>
				<div class="level-right">
					<div class="level-item">
						<div class="field is-grouped">
							<button class="control button is-light has-text-danger" v-on:click.stop="deleteGroup(group.id)">
								<i class="fas fa-trash fa-fw"></i>
							</button>
							<button class="control button is-primary" v-on:click.stop="loadGroup(group.id)">
								<i class="fas fa-chevron-right fa-fw"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	`,
	props: ["group"],
	computed: {
		selected() {
			return this.group.id === this.selectedItemId;
		},
		// Vuex state
		...Vuex.mapState(['selectedItemId'])
	},
	methods: {
		// Vuex actions & mutations
		...Vuex.mapActions(['loadGroup', 'deleteGroup']),
		...Vuex.mapMutations(['setSelectedItem']),
	}
}