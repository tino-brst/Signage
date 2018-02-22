<template>
	<div class="vue-component">
		<h4> Upload Images </h4>
		<form 
			id="upload-images"
			@submit.prevent="submit">
			<p>
				<input
					type="file"
					multiple
					@change="updateFiles($event.target.files)">
			</p>
		</form>
		<ul v-if="files.length > 0 ">
			<li 
				v-for="(file, index) in files"
				:key="index">
				{{ file.name }}
			</li>
		</ul>
		<p>
			<input
				type="submit" 
				value="upload"
				form="upload-images"
				:disabled="files.length === 0">
		</p>
	</div>
</template>

<script>
import Vuex from 'vuex';
import axios from 'axios';

export default {
	data() {
		return {
			files: []
		}
	},
	methods: {
		submit() {
			var images = new FormData();
			this.files.forEach((file) => {
				images.append('images[]', file, file.name);
			})
			this.addImages(images);
		},
		updateFiles(files) {
			this.files = Array.from(files);
		},
		// Vuex actions
		...Vuex.mapActions(['addImages'])
	}
}
</script>