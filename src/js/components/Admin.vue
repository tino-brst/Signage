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
		// api test
		this.$store.dispatch('loadRoot');
	}
}
</script>

<style>
#app {
	font-family: sans-serif;
}
</style>
