var ScreenVC = {
	template: `
		<div class="screen-vc vue-component box" v-on:click.stop="setSelectedItem(screen.id)" :class="{selected: selected}">
			<div class="level">
				<div class="level-left">
					<div class="level-item">
						<p class="is-size-5 is-capitalized"> {{screen.name}} </p>
					</div>
				</div>
				<div class="level-right">
					<div class="level-item">
						<div class="field is-grouped">
							<button class="control button is-light has-text-danger" v-on:click.stop="deleteScreen(screen.id)">
								<i class="fas fa-trash fa-fw"></i>
							</button>
							<button class="control button is-primary" v-on:click.stop="">
								<i class="fas fa-search fa-fw"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
			<figure class="image is-16x9">
				<img src="public/images/image4.jpg" alt="Image">
			</figure>
			<!-- <p class="subtitle has-text-grey has-text-centered"> playlists title </p> -->
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