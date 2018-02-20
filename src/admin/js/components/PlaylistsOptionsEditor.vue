<template>
	<div class="vue-component">
		<h4> Options </h4>
		<template v-if="selectedItem">
			<template v-if="selectedItem.type == 'group'">
				<OptionsGroup :group="selectedItem"/>
			</template>
			<template v-else>
				<OptionsScreen :screen="selectedItem"/>
			</template>
		</template>
		<template v-else>
			Select an item to edit
		</template>
	</div>
</template>

<script>
import Vuex from 'vuex';
import OptionsGroup from './OptionsGroup'
import OptionsScreen from './OptionsScreen'

export default {
	components: {
		OptionsGroup,
		OptionsScreen
	},
	computed: {
		// Vuex getters
		...Vuex.mapGetters(['selectedItem'])
	}
}
</script>