var ScreenVC = {
	template: `
		<div class="screen-vc vue-component" v-on:click.stop="setSelectedItem(screen.id)" :class="{selected: selected}">
			<li>
				<span> {{screen.name}} </span>
				<button v-on:click.stop="deleteScreen(screen.id)"> delete </button>
			</li>
		</div>
	`,
	props: ["screen"],
	computed: {
		selected() {
			return this.screen.id === this.selectedItemId;
		},
		// Vuex state
		...Vuex.mapState(['selectedItemId'])
	},
	methods: {
		// Vuex actions & mutations
		...Vuex.mapActions(['deleteScreen']),
		...Vuex.mapMutations(['setSelectedItem']),
	}
}