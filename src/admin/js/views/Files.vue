<template>
	<div class="vue-component">
		<FormUpload/>

		<hr>

		<h2> Images </h2>
		<div 
			v-if="images.length > 0"
			id="images">
			<ImageItem 
				v-for="image in images"
				:image="image"
				:key="image.id"/>
		</div>
		<div v-else>
			<h4> No images uploaded ... </h4>
		</div>
	</div>
</template>

<script>
import Vuex from 'vuex';
import FormUpload from '../components/FormUpload';
import ImageItem from '../components/ImageItem'

export default {
	components: {
		FormUpload,
		ImageItem
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
	#images {
		display: flex;
		flex-wrap: wrap;
		justify-content: flex-start;
	}
</style>