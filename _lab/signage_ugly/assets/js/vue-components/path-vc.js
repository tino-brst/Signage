var PathVC = {
	template: `
		<div class="path-vc vue-component">
			<h4> Path </h4>
			<ul v-if="!isEmpty">
				<template v-for="(item, index) in items" v-if="index < (items.length - 1)"> 
					<li v-on:click="loadGroup(item.id)"> {{item.name}} </li>
				</template>
				<li><strong> {{items[items.length - 1].name}} </strong></li> 
			</ul>
		</div>
	`,
	props: ["items"],
	computed: {
		isEmpty() {
			return this.items.length == 0;
		}
	},
	methods: {
		// Vuex actions
		...Vuex.mapActions(['loadGroup'])
	}
}