<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Admin </title>
	<base href="<?= base_url() ?>">
	<script src="assets/js/libraries/vue.js"></script>
	<script src="assets/js/libraries/axios.js"></script>
	<script src="assets/js/libraries/vuex.js"></script>
	<script src="assets/js/libraries/buefy.js"></script>
	<!-- <link rel="stylesheet" href="assets/css/buefy.min.css"> -->
	<link rel="stylesheet" href="assets/css/custom.css">
	<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
	<!-- <link type="text/css" rel="stylesheet" href="assets/css/style.css"> -->
	<style type="text/css">

		/* MAIN */

			html {
				height: 100%;
				overflow: hidden;
			}
			body {
				height: 100%;   
				overflow: hidden;  /*makes the body non-scrollable (we will add scrolling to the sidebar and main content containers)*/
				margin: 0px;  /*removes default style*/
			}
			#app {
				height: 100%;
				display: flex;  /*enables flex content for its children*/
				box-sizing: border-box;
			}

			#menu {
				flex: 0 0 16rem;
				padding: 2rem;
				background-color: whitesmoke;
				border-style: solid;
				border-width: 0 1px 0 0 ;
				border-color: lightgray;
			}
			#group-explorer {
				flex: 1;
				display: flex;
	    		flex-direction: column;
			}
			.item-editor {
				flex: 0 0 16rem;
				background-color: #292929;
				border-style: solid;
				border-width: 0 0 0 1px ;
				border-color: darkgray;
			}

		.group-vc {
			user-select: none;
			-moz-user-select: none;
			-khtml-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
		.selected {
			box-shadow: 0 2px 3px hsla(0,0%,4%,.1), 0 0 0 4px #157df04a;
		}
		.message-vc {
			color:  royalblue;
			text-align: right;
		}

		/* NAV */

			.nav {
				display: flex;
				left: 0;
				right: 0;
				padding-left: 1rem; 
				padding-right: 1rem;
			}
			.nav.has-shadow {
				box-shadow: 0 2px 3px hsla(0,0%,4%,.1);
			}
			.has-nav {
				padding-top: 3.25rem;
			}

			.item-editor {}

			.item-editor .nav {
				justify-content: center;
				align-items: center;
				height: 3.25rem;
				border-style: solid;
				border-width: 0 0 1px 0;
				border-color: #5a5a5a;
				background-color: #3b3b3b
			}
			#group-explorer .nav {
				border-style: solid;
				border-width: 0 0 1px 0;
				border-color: #e6e6e6;
			}

			.path-vc {
				display: flex;
				align-items: center;
			}
			.path-vc i {
				margin: 0.25rem;
			}

		/* MODAL */
		
			.modal-content {
				width: 20rem;
			}
			.modal-card {
				width: auto;
			}
			.modal-card-foot {
				justify-content: flex-end;
			}
	
		.group-content-vc {
			padding-left: 2rem;
			flex: 1;
			overflow-y: auto;
			display:flex;
			flex-direction: column;
		}

		#empty-group {
			padding-top: 8rem;
		}

		.groups-container-vc .header {
			margin: 2rem 0 1rem 0;
		}
		.screens-container-vc .header {
			margin: 2rem 0 1rem 0;
		}

		.grid {
			display: flex;
			flex-wrap: wrap;
			flex-direction: row;
			justify-content: flex-start;
			align-items: flex-start;

		}
		.group-vc, .screen-vc {
			width: 18rem;
			margin-right: 2rem;
			margin-bottom: 2rem;
		}

		.group-vc:hover,.screen-vc:hover {
			box-shadow: 0 2px 3px hsla(0,0%,4%,.1), 0 0 0 3px #157df04a;
		}

		.screen-vc img {
			border-radius: 5px;
		}



	</style>
</head>
<body>

	<div id="app">
		<div id="menu">
			<aside class="menu">
				<p class="menu-label">
					General
				</p>
				<ul class="menu-list">
					<li><a class="is-active"> Groups and Screens</a></li>
					<li><a> Upload Files </a></li>
				</ul>
				<p class="menu-label">
					Content
				</p>
				<ul class="menu-list">
					<li><a> Basic </a></li>
					<li><a> Playlists </a></li>
					<li><a> Schedules </a></li>
				</ul>
				<p class="menu-label">
					Others
				</p>
				<ul class="menu-list">
					<li><a>...</a></li>
				</ul>
			</aside>
		</div>

		<div id="group-explorer">
			<group-nav-vc class="nav is-fixed-top">
				<div class="navbar-menu is-active">
					<div class="navbar-start">
						<div class="navbar-item">
							<div class="field is-grouped">
								<p class="control">
									<button class="button is-primary" v-on:click="loadGroup(currentGroup.parent_id)" v-bind:disabled="currentGroupIsRoot">
										<i class="fas fa-chevron-left fa-fw"></i>
									</button>
								</p>
								<p class="control">
									<path-vc :items="currentGroup.path"></path-vc>
								</p>
							</div>
						</div>
					</div>
					<div class="navbar-end">
						<div class="navbar-item">
							<div class="field is-grouped">
								<p class="control">
									<button class="button is-primary" v-on:click="showGroupForm = true"> add group </button>
								</p>
								<p class="control">	
									<button class="button is-primary" v-on:click="showScreenForm = true"> add screen </button>
								</p>
							</div>
						</div>
					</div>
				</div>
			</group-nav-vc>

			<group-content-vc></group-content-vc>
		</div>

		<aside class="item-editor">
			<div class="nav is-fixed-top"> 		
				<h4 class="subtitle is-4 has-text-grey-lighter has-text-centered"> Options </h4>
			</div>
		</aside>

		<screen-form-vc :active="showScreenForm" v-on:close="hideScreenForm"></screen-form-vc>
		<group-form-vc :active="showGroupForm" v-on:close="hideGroupForm"></group-form-vc>

<!-- 

		<item-editor-vc></item-editor-vc>
		<message-vc></message-vc> -->

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
			Vue.use(Buefy.default);
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