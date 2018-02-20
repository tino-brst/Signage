<template>
	<div id="app">

		<Menu/>

		<keep-alive>
			<router-view/>
		</keep-alive>
		
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
		padding: 1rem;
	}
	.vue-component {
		background: #ccc6;
		border-radius: 0.3rem;
		margin-top:	0.5rem;
		margin-bottom: 0.5rem;
		padding: 0.5rem 1rem;
	}
	h4, h2 {
		margin: 0;
	}
</style>
