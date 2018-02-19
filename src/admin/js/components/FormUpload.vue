<template>
	<div class="vue-component">
		<h4> Upload File </h4>
		<form 
			id="upload-file"
			@submit.prevent="submit">
			<p>
				<input
					type="file"
					@change="updateFilePath($event.target.value)">
			</p>
		</form>
		<p>
			<input 
				type="submit" 
				value="upload"
				form="upload-file">
		</p>
	</div>
</template>

<script>
import axios from 'axios';

export default {
	data() {
		return {
			filePath: ""
		}
	},
	methods: {
		submit() {
			var data = new FormData();
			data.set('userfile', this.filePath);
			axios({
				method: 'put',
				url: API_URL + 'images',
				data: data
			})
				.then(response => {
					console.log('uploaded! :D ' + this.filePath)
				})
				.catch(error => {
					
				})
		},
		updateFilePath(path) {
			this.filePath = path;
		}
	}
}
</script>