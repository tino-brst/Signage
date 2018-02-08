<template>
	<div 
		class="vue-component"
		@click="resetSelectedItem">
		<template v-if="!currentGroupIsEmpty">
			<div v-if="groups.length">
				<h4> Groups </h4>
				<ContentGroup 
					v-for="group in groups" 
					:group="group" 
					:key="group.id"/>
			</div>
			<div v-if="screens.length">
				<h4> Screens </h4>
				<ContentScreen 
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
import ContentGroup from './ContentGroup';
import ContentScreen from './ContentScreen';

export default {
	components: {
		ContentGroup,
		ContentScreen
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