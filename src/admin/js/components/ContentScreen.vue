<template>
	<div 
		:class="{ selected: selected }"
		class="vue-component" 
		@click.stop="setSelectedItem(screen.id)">
		<li>
			<span> {{ screen.name }} </span>
			<button @click.stop="deleteItem"> delete </button>
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
		deleteItem() {
			this.deleteScreen(this.screen.id)
				.then(response => {
					// anuncio que se elimino la pantalla
					this.$socket.emit('screenDeleted', this.screen.udid);
				})
				.catch(error => {

				});
		},
		// Vuex actions & mutations
		...Vuex.mapActions(['deleteScreen']),
		...Vuex.mapMutations(['setSelectedItem']),
	}
}
</script>