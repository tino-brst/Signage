<template>
	<div 
		class="vue-component" 
		@click.stop="setSelectedItem(screen.id)" 
		:class="{ selected: selected }">
		<li>
			<span> {{ screen.name }} </span>
			<button @click.stop="deleteScreen(screen.id)"> delete </button>
		</li>
	</div>
</template>

<script>
import Vuex from 'vuex';

export default {
	props: {
		screen: {
			type: Object,
			default: () => { return {} }
		}
	},
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
</script>