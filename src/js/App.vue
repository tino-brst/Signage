<template>
	<div id="app">
		<h1> {{ message }} </h1>
	</div>
</template>

<script>
	export default {
		name: 'app',
		data () {
			return {
				message: 'Learning Webpack (and Git ...)'
			}
		}
	}
</script>

<style>
	#app {
		font-family: sans-serif;
	}
</style>
