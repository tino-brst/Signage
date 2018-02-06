var ItemEditorVC = {
	template: `
		<div class="item-editor-vc vue-component">
			<h4> Item Editor </h4>
			<template v-if="selectedItem">
				<template v-if="selectedItem.type == 'group'">
					<group-options-vc :group="selectedItem"></group-options-vc>
				</template>
				<template v-else>
					<screen-options-vc :screen="selectedItem"></screen-options-vc>
				</template>
			</template>
			<template v-else>
				Select an item to edit
			</template>
		</div>
	`,
	components: {
		"group-options-vc": GroupOptionsVC,
		"screen-options-vc": ScreenOptionsVC
	},
	computed: {
		// Vuex getters
		...Vuex.mapGetters(['selectedItem'])
	}
}