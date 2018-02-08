<template>
	<div id="app">
		<Menu/>
		<router-view/>
	</div>
</template>

<script>
import Menu from './components/Menu'

export default {
	components: {
		Menu
	}
}
</script>

<style>
	html {
		font-family: sans-serif;
		background-color: whitesmoke;
	}
	.vue-component {
		background: #ccc6;
		border-radius: 0.3rem;
		margin-top:	0.5rem;
		margin-bottom:	0.5rem;
		padding: 0.5rem; 
	}
	h4 {
		margin: 0;
	}
</style>
