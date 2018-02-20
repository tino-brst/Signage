<template>
	<div class="vue-component">

		<h2> Images </h2>

		<div 
			v-if="images.length > 0"
			id="images">
			<ImagesItem 
				v-for="image in images"
				:image="image"
				:key="image.id"/>
		</div>

		<div v-else>
			<h4> No images uploaded ... </h4>
		</div>

		<hr>

		<ImagesForm/>
		
	</div>
</template>

<script>
import Vuex from 'vuex';
import ImagesForm from '../components/ImagesForm';
import ImagesItem from '../components/ImagesItem'

export default {
	components: {
		ImagesForm,
		ImagesItem
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
		margin-top: 0.5rem;
	}
	#images > .vue-component:hover {
		background-color: #b9b9b966;
	}
</style>