<template>
	<div class="vue-component">
		<h4> Upload File </h4>
		<form 
			id="upload-files"
			@submit.prevent="submit">
			<p>
				<input
					type="file"
					multiple
					@change="updateFiles($event.target.files)">
			</p>
		</form>
		<p>
			<input
				type="submit" 
				value="upload"
				form="upload-files">
		</p>
	</div>
</template>

<script>
import axios from 'axios';

export default {
	data() {
		return {
			files: []
		}
	},
	methods: {
		submit() {
			var data = new FormData();
			this.files.forEach((file) => {
				data.append('images[]', file, file.name);
			})
			axios.post(API_URL + 'images', data)
				.then(response => {
				
				})
				.catch(error => {
					
				})
		},
		updateFiles(files) {
			this.files = Array.from(files);
		}
	}
}
</script>