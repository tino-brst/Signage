var GroupVC = {
	template: `
		<div class="group-vc vue-component" v-on:click.stop="setSelectedItem(group.id)" :class="{selected: selected}">
			<li>
				<span> {{group.name}} </span>
				<button v-on:click.stop="deleteGroup(group.id)"> delete </button>
				<button v-on:click.stop="loadGroup(group.id)"> &rarr; </button>
			</li>
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