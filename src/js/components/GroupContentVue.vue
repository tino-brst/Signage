<template>
	<div class="vue-component">
		<div 
			class="group-content-vc vue-component" 
			@click="resetSelectedItem">
			<template v-if="!currentGroupIsEmpty">
				<!-- AGREGAR V-IF  -->
				<h4> Groups </h4>
				<!-- <group-vc v-for="group in groups" :group="group" :key="group.id"></group-vc> -->
				<h4> Screens </h4>
				<!-- <screen-vc v-for="screen in screens" :screen="screen" :key="screen.id"></screen-vc> -->
			</template>
			<template v-else>
				<h4> No items in this group ... </h4>
			</template>
		</div>
	</div>
</template>

<script>
import Vuex from 'vuex';
// import GroupVue from './GroupVue';
// import ScreenVue from './ScreenVue';

export default {
	components: {
		// GroupVue,
		// ScreenVue
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
</script>