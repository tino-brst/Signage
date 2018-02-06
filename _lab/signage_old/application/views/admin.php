<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title> Admin </title>
		<script src="https://unpkg.com/vue"></script>
		<script src="https://unpkg.com/buefy"></script>
		<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
		<link href="https://use.fontawesome.com/releases/v5.0.1/css/all.css" rel="stylesheet">
		<link rel="stylesheet" href="https://unpkg.com/buefy/lib/buefy.min.css">
		<style type="text/css">
		</style>
	</head>
	<body>
		<div id="app">
			<nav class="navbar is-info">
			  	<div class="navbar-brand">
					<h1 class="navbar-item title"> Signage </h1>
			  	</div>
				<div class="navbar-menu">
					<div class="navbar-start">
						<a class="navbar-item is-active" href="admin">
							<span>Screens</span>
						</a>
						<a class="navbar-item" href="admin">
							<span>Playlists</span>
						</a>
					</div>
				</div>
			</nav>

			<p class="block">
			<div class="columns is-centered">
  				<div class="column is-three-fifths">
  					<div class="box">
  						<div class="level">
  							<div class="level-left">
  								<div class="level-item">
									<h1 class="title has-text-grey"> Screens </h1>
  								</div>
  							</div>
  							<div class="level-right">
  								<div class="level-item">
  									<button class="button is-info" @click="toggleModal()"> Add screen </button>
  								</div>
  							</div>
  						</div>

						<div class="block">
							<b-table
								:data="screens"
								:striped="true"
								:hoverable="true">
								<template slot-scope="props">
									<b-table-column label="ID" width="40" class="has-text-grey-light has-text-right">
										{{ props.row.screen_id }}
									</b-table-column>
									<b-table-column label="Name">
										{{ props.row.screen_name }}
									</b-table-column>
									<b-table-column label="Playlist">
										{{ props.row.playlist_name }}
									</b-table-column>
									<b-table-column custom-key="actions" :numeric="true">
										<button class="button has-text-grey" @click="deleteScreen(props.row.screen_id)">
											<span class="icon">
										  		<i class="fa fa-edit"></i>
											</span>
										</button>											
										<button class="button has-text-grey" @click="deleteScreen(props.row.screen_id)">
											<span class="icon">
										  		<i class="fa fa-trash"></i>
											</span>
										</button>	
									</b-table-column>								
								</template>
							</b-table>
						</div>

						<b-modal :active.sync="modalActive">
							<div class="modal-card">
								<header class="modal-card-head">
									<p class="modal-card-title"> New Screen </p>
								</header>
								<section class="modal-card-body">
									<form id="newScreenForm">
										<b-field label="Pin">
											<b-input placeholder="enter the pin on screen" v-model='newScreen.pin'></b-input>
										</b-field>
										<b-field label="Name">
											<b-input placeholder="choose a name" v-model='newScreen.name'></b-input>
										</b-field>
										<b-field label="Playlist">
											<b-select placeholder="Select a playlist" v-model='newScreen.playlistID'>
												<option v-for="playlist in playlists" :value="playlist.id"> {{playlist.name}} </option>
											</b-select>
										</b-field>
									</form>
								</section>
								<footer class="modal-card-foot">
									<button class="button" type="button" @click="toggleModal()"> Cancel </button>
									<button class="button is-info" @click="addScreen()" > Done </button>
								</footer>
							</div>
						</b-modal>		
  					</div>
  				</div>
			</div>
			</p>		
		</div>

		<script>
			Vue.use(Buefy.default);
			var apiUrl = "<?= base_url('index.php/api/') ?>";

			var app = new Vue({
				el: '#app',
				data: {
					screens: [],
					playlists: [],
					newScreen: { 
						id: '',
						pin: '',
						name: '',
						playlistID: ''
					},
					modalActive: false
				},
				methods: {
					addScreen() {
						// obtengo el id de la pantalla con el pin ingresado
						axios.get(apiUrl + 'get_screen_for_pin/' + this.newScreen.pin).then(
							response => {
								var screenID = response.data;
								// junto los datos de la nueva pantalla
								var data = new FormData();
								data.append("screenID", screenID);
								data.append("name", this.newScreen.name);
								data.append("playlistID", this.newScreen.playlistID);
								// solicito creacion de nueva pantalla
								axios.post(apiUrl + "new_screen", data).then(
									response => {
										this.toggleModal();
										this.updateScreens();
									});
							});
					},
					deleteScreen(id) {
						axios.delete(apiUrl + "delete_screen/" + id).then(
							response => {
								this.updateScreens();
							}).catch(e => {

					    	});
					},
					updateScreens() {
						axios.get(apiUrl+'get_screens_with_playlists').then(
							response => {
								this.screens = response.data;
							});
					},
					updatePlaylists() {
						axios.get(apiUrl+'get_playlists').then(
							response => {
								this.playlists = response.data;
							});
					},
					toggleModal() {
						this.modalActive = !this.modalActive;
						this.newScreen.id = '';
						this.newScreen.pin = '';
						this.newScreen.name = '';
						this.newScreen.playlistID = '';
					}
				},
				created() {
					this.updateScreens();
					this.updatePlaylists();
				}
			});
		</script>
	</body>
</html>