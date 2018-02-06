var PathVC = {
	template: `
		<div class="path-vc vue-component">
			<template v-if="items.length > 0">
				<template v-for="(item, index) in items" v-if="index < (items.length - 1)"> 
					<button v-on:click="loadGroup(item.id)" class="button is-light has-text-grey"> {{item.name}} </button>
					<i class="fas fa-chevron-right has-text-grey-lighter"></i>
				</template>
				<button class="button is-light"> {{items[items.length - 1].name}} </button>
				<!-- <li><strong> {{items[items.length - 1].name}} </strong></li>  -->
			</template>
		</div>
	`,
	props: ["items"],
	methods: {
		// Vuex actions
		...Vuex.mapActions(['loadGroup'])
	}
}