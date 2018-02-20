<template>
	<div 
		id="menu"
		class="vue-component">
		<nav>
			<router-link to="/groups-and-screens"> Groups And Screens </router-link>
			<router-link to="/playlists"> Playlists </router-link>
			<router-link to="/images"> Images </router-link>
		</nav>
	</div>
</template>

<style>
a {
	margin-right: 1rem;
	margin-left: 1rem;
	font-size: 1rem;
	text-decoration: none;
	color: gray;
}
#menu {
	margin-bottom: 1rem;
}
nav {
	display: flex;
	justify-content: flex-end;
}
.router-link-active {
	font-weight: bold;
	color: dimgray;
}
</style>
