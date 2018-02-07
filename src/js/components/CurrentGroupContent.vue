<template>
	<div 
		class="vue-component"
		@click="resetSelectedItem">
		<template v-if="!currentGroupIsEmpty">
			<div v-if="groups.length">
				<h4> Groups </h4>
				<ItemGroup 
					v-for="group in groups" 
					:group="group" 
					:key="group.id"/>
			</div>
			<div v-if="screens.length">
				<h4> Screens </h4>
				<ItemScreen 
					v-for="screen in screens" 
					:screen="screen" 
					:key="screen.id"/>
			</div>
		</template>
		<template v-else>
			<h4> No items in this group ... </h4>
		</template>
	</div>
</template>

<script>
import Vuex from 'vuex';
import ItemGroup from './ItemGroup';
import ItemScreen from './ItemScreen';

export default {
	components: {
		ItemGroup,
		ItemScreen
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

<style> 
.selected {
	border-color: gray;
	border-width: thin;
	border-style: solid;
}
</style>