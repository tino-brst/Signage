<template>
	<div 
		class="vue-component" 
		@click.stop="setSelectedItem(group.id)" 
		:class="{ selected: selected }">
		<li>
			<span> {{ group.name }} </span>
			<button @click.stop="deleteGroup(group.id)"> delete </button>
			<button @click.stop="loadGroup(group.id)"> &rarr; </button>
		</li>
	</div>
</template>

<script>
import Vuex from 'vuex';

export default {
	props: {
		group: {
			type: Object,
			default: () => { return {} }
		}
	},
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
</script>