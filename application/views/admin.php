<!DOCTYPE html>
<html class="has-navbar-fixed-top">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Admin </title>
	<base href="<?= base_url() ?>">
	<script src="assets/js/libraries/vue.js"></script>
	<script src="assets/js/libraries/axios.js"></script>
	<script src="assets/js/libraries/vuex.js"></script>
	<style type="text/css">
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
		.group-vc {
			user-select: none;
			-moz-user-select: none;
			-khtml-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
		.selected {
			border-color: gray;
			border-width: thin;
			border-style: solid;
		}
		.message-vc {
			color:  royalblue;
			text-align: right;
		}
		h4 {
			margin: 0;
		}
	</style>
</head>
<body>
	<div id="app">

		<h1> {{currentGroup.name}} </h1>

		<screen-form-vc v-if="showScreenForm" v-on:done="hideScreenForm" v-on:cancel="hideScreenForm"></screen-form-vc>
		<group-form-vc v-if="showGroupForm" v-on:done="hideGroupForm" v-on:cancel="hideGroupForm"></group-form-vc>
		<hr>
		<group-nav-vc>
			<button v-on:click="loadGroup(currentGroup.parent_id)" v-bind:disabled="currentGroupIsRoot"> &larr; </button>
			<button v-on:click="showGroupForm = true"> + add group </button>
			<button v-on:click="showScreenForm = true"> + add screen </button>
			<path-vc :items="currentGroup.path"></path-vc>
		</group-nav-vc>
		<hr>
		<group-content-vc></group-content-vc>
		<hr>
		<item-editor-vc></item-editor-vc>
		<message-vc></message-vc>

	</div>

		<!-- store -->
		<script type="text/javascript" src="assets/js/store/store.js"></script>

		<!-- componentes (pasar a un solo archivo) -->
		<!-- OJO con el orden de las definiciones! si uno es interno a otro tengo que definirlo primero primero -->
		<script type="text/javascript" src="assets/js/vue-components/path-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/group-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/screen-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/groups-container-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/screens-container-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/screen-form-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/group-form-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/message-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/group-options-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/screen-options-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/item-editor-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/group-nav-vc.js"></script>
		<script type="text/javascript" src="assets/js/vue-components/group-content-vc.js"></script>

		<!-- app -->
		<script>
			var apiUrl = "<?= base_url('index.php/api/') ?>";
			Vue.prototype.$eventBus = new Vue();

			var app = new Vue({
				el: '#app',
				store: store,
				components: {
					"path-vc": PathVC,
					"screen-form-vc": ScreenFormVC,
					"group-form-vc": GroupFormVC,
					"message-vc": MessageVC,
					"item-editor-vc": ItemEditorVC,
					"group-nav-vc": GroupNavVC,
					"group-content-vc": GroupContentVC
				},
				data: {
					showScreenForm: false,
					showGroupForm: false
				},
				computed: {
					// Mapeo getters y state del store (evito tener que accederlos via this.$store.getters...)
					...Vuex.mapState(['currentGroup']),
					// propiedades computadas del grupo actual
					currentGroupIsRoot() {
						return this.currentGroup.path.length == 1;
					},
				},
				created() {
					// cargo el punto de partida de la jerarquia de grupos
					this.loadRoot();
					this.loadPlaylists();
				},
				methods: {
					...Vuex.mapActions(['loadRoot', 'loadGroup', 'loadPlaylists']),
					// visibilidad de "forms"
					hideScreenForm() {
						this.showScreenForm = false;
					},
					hideGroupForm() {
						this.showGroupForm = false;
					}
				}
			});
		</script>
	</body>
</html>