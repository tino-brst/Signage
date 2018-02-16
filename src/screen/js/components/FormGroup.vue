<template>
	<div class="vue-component">
		<h4> New Group </h4>
		<form 
			id="new-group"
			@submit.prevent="submit">
			<p>
				<label for="name"> name </label>
				<input 
					type="text"
					v-model="name">
			</p>
		</form>
		<p>
			<input 
				type="submit" 
				value="done"
				form="new-group">
			<input 
				type="button"
				value="cancel"
				@click="$emit('hide')">
		</p>
	</div>
</template>

<script>
import Vuex from 'vuex';

export default {
	data() {
		return {
			name: ""
		}
	},
	computed: {
		// Vuex state
		...Vuex.mapState(['currentGroup'])
	},
	methods: {
		submit() {
			var group = {
				name: this.name,
				parentId: this.currentGroup.id,
				extraFields: {}
			}
			this.addGroup(group)
				.then(response => {
					// oculto el menu
					this.$emit('hide');
				})
				.catch(error => {

				});
		},
		// Vuex actions
		...Vuex.mapActions(['addGroup'])
	}
}
</script>