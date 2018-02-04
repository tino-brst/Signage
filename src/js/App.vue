<template>
	<div id="app">
		<h1> {{ message }} </h1>
	</div>
</template>

<script>
import axios from 'axios'

export default {
	name: 'App',
	data () {
		return {
			message: 'Learning Webpack (and Git ...)'
		}
	},
	created() {
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
