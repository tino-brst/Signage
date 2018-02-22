<template>
	<div class="vue-component">
		<h4> Options </h4>
		<template v-if="selectedPlaylist">
			<OptionsPlaylist :playlist="selectedPlaylist"/>
			<ImagePicker/>
		</template>
		<template v-else>
			Select a playlist to edit
		</template>
	</div>
</template>

<script>
import Vuex from 'vuex';
import OptionsPlaylist from './OptionsPlaylist';
import ImagePicker from './ImagePicker';

export default {
	components: {
		OptionsPlaylist,
		ImagePicker
	},
	computed: {
		// Vuex getters
		...Vuex.mapGetters(['selectedPlaylist'])
	}
}
</script>