<template>
	<div class="vue-component">

		<h2> Image Picker </h2>

		<Draggable 
			id="imagePicker"
			:list="images"
			:options="{group: {name: 'items', pull: 'clone', put: false}, sort: false}">
			<div
				v-for="(image, index) in images"
				:key="index"
				class="imagePickerItem">
				<img :src="image.location">
			</div>
		</Draggable>
	</div>
</template>

<script>
import Vuex from 'vuex';
import Draggable from 'vuedraggable';

export default {
	components: {
		Draggable
	},
	computed: {
		// Vuex state - mapeo state del store (evito tener que accederlos via this.$store.state...) -
		...Vuex.mapState(['images'])
	},
	created() {
		this.loadImages();
	},
	methods: {
		// Vuex actions
		...Vuex.mapActions(['loadImages'])
	}
}
</script>

<style>
	#imagePicker {
		display: flex;
		flex-wrap: wrap;
		margin-top: 0.5rem;
		margin-bottom: 0.5rem;
	}
	#imagePicker img {
		height: 5rem;
		object-fit: cover;
		border-radius: 0.5rem;
	}
	.imagePickerItem {
		display: flex;
		padding: 0.5rem;
	}
</style>
