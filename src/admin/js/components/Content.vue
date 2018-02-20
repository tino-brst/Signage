<template>
	<div 
		class="vue-component"
		@click="resetSelectedItem">
		<div 
			id="content"
			v-if="!currentGroupIsEmpty">
			<div v-if="groups.length">
				<h2> Groups </h2>
				<ContentGroup
					v-for="group in groups" 
					:group="group" 
					:key="group.id"/>
			</div>
			<div v-if="screens.length">
				<h2> Screens </h2>
				<ContentScreen 
					v-for="screen in screens" 
					:screen="screen" 
					:key="screen.id"/>
			</div>
		</div>
		<div v-else>
			<h4> No items in this group ... </h4>
		</div>
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

<style scoped> 
h2 {
	margin: 0;
}
.selected {
	border-color: gray;
	border-width: thin;
	border-style: solid;
}
</style>