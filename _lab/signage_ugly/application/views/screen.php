<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Screen </title>
	<base href="<?= base_url() ?>">
	<script src="assets/js/libraries/vue.js"></script>
	<script src="assets/js/libraries/axios.js"></script>
	<style type="text/css">
		html {
			font-family: sans-serif;
			background-color: whitesmoke;
		}
	</style>
</head>
<body>
	<div id="app">
		<h1 v-if="showSetup"> Setup Pin: {{setupPin}} </h1>
		<img v-else v-for="item in content.items" :src="item.location">
	</div>
	<script>
			var apiUrl = "<?= base_url('index.php/api/') ?>";

			var app = new Vue({
				el: '#app',
				components: {
				},
				data: {
					screen: {
						udid: '<?=$screen_udid?>'
					},
					content: {},
					showSetup: false,
					setupPin: {}
				},
				computed: {
				},
				created() {
					// veo si la pantalla ya es parte del sistema
					axios.get(apiUrl + 'screen', {params: {
						udid: this.screen.udid,
						includeContent: false
					}})
					.then(response => {
						// si ya es parte del sistema, muestro su contenido correspondiente
						this.screen = response.data;
						this.loadContent();
					})
					.catch(error => {
						// si no, inicio el modo setup (muestra pin, etc)
						this.startSetup()
					});

				},
				methods: {
					startSetup() {
						axios.put(apiUrl + 'setup', {udid: this.screen.udid})
						.then(response => {
							this.setupPin = response.data.setup.pin;
							this.showSetup = true;
						})
						.catch(error => {
							console.log(error.response.data.message);
						})
					},
					loadContent() {
						// obtengo contenido de la pantalla
						axios.get(apiUrl + 'playlist', {params: {
							id: this.screen.playlist_id,
							includeContent: true
						}})
						.then(response => {
							// si ya es parte del sistema, muestro su contenido correspondiente
							this.content = response.data;
						})
						.catch(error => {

						});
					}
				}
			});
		</script>
</body>
</html>