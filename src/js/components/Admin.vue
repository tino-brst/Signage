<template>
	<div id="app">
		<h1> {{ message }} </h1>
	</div>
</template>

<script>
import axios from 'axios';

export default {
	name: 'Admin',
	data() {
		return {
			message: 'Codeigniter + Vue + Webpack :D'
		}
	},
	created() {
		// axios test
		axios.get('https://jsonplaceholder.typicode.com/posts/1')
			.then(response => {
				console.log(response.data);
			})
			.catch(e => {
				console.log(response.data);
			});
	}
}
</script>

<style>
#app {
	font-family: sans-serif;
}
</style>
