<template>
	<div class="vue-component">
		<nav>
			<router-link to="/groups-and-screens"> Groups And Screens </router-link>
			<router-link to="/playlists"> Playlists </router-link>
		</nav>
	</div>
</template>

<style>
a {
	margin: 1rem;
	font-size: 1.2rem;
	text-decoration: none;
	color: gray;
}
.router-link-active {
	font-weight: bold;
	color: black;
}
</style>
