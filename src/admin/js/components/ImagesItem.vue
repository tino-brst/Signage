<template>
	<div class="image-item vue-component">
		<img :src="image.location">
		<button @click="deleteImage(image.id)"> delete </button>
	</div>
</template>

<script>
import Vuex from 'vuex';

export default {
	props: {
		image: {
			type: Object,
			default: () => { return {} }
		}
	},
	methods: {
		// Vuex actions & mutations
		...Vuex.mapActions(['deleteImage']),
	}
}
</script>

<style scoped>
	.image-item {
		display: flex;
		flex-wrap: nowrap;
		flex-direction: column;
		justify-items: flex-end;
		margin-top: 0;
		margin-right: 1rem;
		margin-bottom: 1rem;
	}
	img {
		height: 7rem;
		object-fit: cover;
		border-radius: 0.5rem;
		margin-bottom: 0.5rem;
	}
	button {
		margin-left: auto;
	}
</style>
