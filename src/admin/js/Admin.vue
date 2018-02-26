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
		padding: 0.5rem;
		margin-top: 0.5rem;
		margin-bottom: 0.5rem;
	}
	h4 {
		margin-top: 0.5rem;
		margin-bottom: 0.5rem;
	} 
	h2 {
		margin-top: 0.5rem;
		margin-bottom: 0.5rem;
	}
	p {
		margin-top: 0.5rem;
		margin-bottom: 0.5rem;
	}
	hr {
		border: 0;
		height: 1px;
		background: #c5c5c5;
	}
</style>
