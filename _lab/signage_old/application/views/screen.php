<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title> Screen </title>
		<script src="https://unpkg.com/vue"></script>
		<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.1/css/bulma.min.css">
		<base href=<?= base_url() ?>>
		<style type="text/css">
			body {
				margin: 0px;
			}
			#slides {
				position: absolute;
				display: grid;
				grid-template-columns: 1fr;
				grid-template-rows: 80% 20%;
				width: 100%;
				height: 100%;
				background-color: black;
			}
			#slides #content {
				grid-column: 1/2;
				grid-row: 1/3;
			}
			#slides #info {
				grid-column: 1/2;
				grid-row: 2/3;
				background-color: black;
				opacity: 0.7;      
			}
			#slides #info #title {
				position: relative;
				top: 50%;
				transform: translateY(-50%);
				padding: 0 50px;
				color: white;
				font-size: 4em;
			}
			img {
				position: absolute;
				width: 100%;
				height: 100%;
				object-fit: cover;
				transition: 1s;
			}
			img.inactive {
				opacity: 0;
			}
			img.active {
				opacity: 1;
			}
		</style>
	</head>
	<body>
		<div id="app">	

			<section v-if="showPairingPin" class="hero is-dark is-bold is-fullheight">
				<div class="hero-body">
					<div class="container has-text-centered">
							<h2 class="subtitle has-text-grey" style="font-size: 3rem">
								setup pin <br><br>
							</h2>
						<h1 class="title" style="font-size: 8rem">
							{{ formattedSetupPin }}
						</h1>
					</div>
				</div>
			</section>

			<div id='slides' v-on:click='nextImage'>
				<div id='content'>
					<img v-for='(image,index) in playlist.items' v-bind:src='image.location' v-bind:class='index==currentImageIndex ? "active" : "inactive"'>
				</div>
				<div id='info'>
					<div id=title> {{playlist.name}} </div>
				</div>
			</div>
		</div>

		<script>
			var apiUrl = "<?= base_url('index.php/api/') ?>";

			var app = new Vue({
				el: '#app',
				data: {
					screen: {
						id: ''
					},
					playlist: {
						id: '',
						name: '',
						items: []
					},
					currentImageIndex: 0,
					setupPin: '',
					showPairingPin: false
				},
				methods: {
					setup() {
						// chequeo si la pantalla ya es parte del sistema
						axios.get(apiUrl + 'get_screens/' + this.screen.id).then(
							response => {
								if (response.data == null) {
									// si no la encuentro procedo a agregarla
									this.pairScreen();
								} else {
									// si la encuentro obtengo su playlist correspondiente
									this.getPlaylist();
								}
							});
					},
					pairScreen() {
						// solicito pin para mostrar en pantalla durante setup
						axios.get(apiUrl + 'get_setup_pin/' + this.screen.id).then(
							response => {
								this.setupPin = response.data.toString();
								this.showPairingPin = true;
							});
					},
					getPlaylist() {
						axios.get(apiUrl + 'get_playlist_for_screen/' + this.screen.id).then(
							response => {
								this.playlist = response.data;
							});
					},
					nextImage() {
						
						this.currentImageIndex = (this.currentImageIndex + 1) % this.playlist.items.length;
					},
				},
				computed: {
					formattedSetupPin() {
						return this.setupPin.split('').join(' ');
					}
				},
				created() {
					this.screen.id = '<?=$screen_id?>';
					this.setup();
				}
			});
		</script>
	</body>
</html>