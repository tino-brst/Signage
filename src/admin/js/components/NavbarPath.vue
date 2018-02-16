<template>
	<div class="vue-component">
		<ul 
			id="path" 
			v-if="items.length > 0">
			<template v-for="(item, index) in items"> 
				<li 
					v-if="index < (items.length - 1)"
					:key="item.id"
					@click="loadGroup(item.id)">
					{{ item.name }} /
				</li>
			</template>
			<li><strong> {{ items[items.length - 1].name }} </strong></li> 
		</ul>
	</div>
</template>

<script>
import Vuex from 'vuex';

export default {
	props: {
		items: {
			type: Array,
			default: () => { return [] }
		}
	},
	methods: {
		// Vuex actions
		...Vuex.mapActions(['loadGroup'])
	}
}
</script>

<style>
#path {
	list-style: none;
	padding: 0;
	margin: 0;
}
#path li {
	display: inline;
}
</style>