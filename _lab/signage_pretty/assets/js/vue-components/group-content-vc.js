var GroupContentVC = {
	template: `
		<div class="group-content-vc vue-component" v-on:click="resetSelectedItem">
			<template v-if="!currentGroupIsEmpty">
				<groups-container-vc v-if="groups.length > 0">
						<group-vc v-for="group in groups" :group="group" :key="group.id"></group-vc>
				</groups-container-vc>
				<screens-container-vc  v-if="screens.length > 0">
						<screen-vc v-for="screen in screens" :screen="screen" :key="screen.id"></screen-vc>
				</screens-container-vc>
			</template>
			<template v-else>
				<div id="empty-group">
						<div class="container has-text-centered">
							<h1 class="title is-spaced">
								No items in this group 
							</h1>
							<h2 class="subtitle has-text-grey">
								add new screens and groups
							</h2>
						</div>
				</div>
			</template>
		</div>
	`,
	components: {
		"group-vc": GroupVC,
		"screen-vc": ScreenVC,
		"groups-container-vc": GroupsContainerVC,
		"screens-container-vc": ScreensContainerVC
	},
	computed: {
		currentGroupIsEmpty() {
			return this.groups.length + this.screens.length == 0;
		},
		// Vuex getters
		...Vuex.mapGetters(['groups', 'screens']),
	},
	methods: {
		// Vuex mutations
		...Vuex.mapMutations(['resetSelectedItem']),
	}
}