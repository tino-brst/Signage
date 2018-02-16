<template>
	<div class="vue-component">
		<h4> {{ group.name }} </h4>
		<form @submit.prevent="save">
			<p>
				<label> name </label>
				<input 
					type="text" 
					v-model="newValues.name" 
					placeholder="new name">
			</p>
			<p>
				<input 
					type="submit" 
					value="save">
				<input 
					type="button" 
					value="cancel"
					@click="cancel">
			</p>
		</form>
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
	data() {
		return {
			// guardo una copia del item seleccionado para no editarlo directamente
			// (los "props" solo se acceden, no se deben modificar)
			newValues: Object.assign({}, this.group)
		}
	},
	watch: {
		group(newSelectedGroup) {
			this.newValues = Object.assign({}, newSelectedGroup);
		}
	},
	methods: {
		save() {
			var updatedGroup = {
				id: this.group.id,
				name: this.newValues.name,
				extraFields: {}
			}
			this.updateSelectedGroup(updatedGroup);
		},
		cancel() {
			// restauro el valor inicialmente asignado
			this.newValues = Object.assign({}, this.group);
		},
		// Vuex actions
		...Vuex.mapActions(['updateSelectedGroup'])
	}
}
</script>