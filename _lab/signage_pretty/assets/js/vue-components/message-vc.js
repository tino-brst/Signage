var MessageVC = {
	template: `
		<div class="message-vc vue-component" v-show="show">
			<h4> {{message}} </h4>
		</div>
	`,
	data() {
		return {
			message: "",
			show: false
		}
	},
	created() {
		this.$eventBus.$on('newMessage', (message) => {
			this.flash(message);
			this.hide();
		});
	},
	beforeDestroy() {
		this.$eventBus.$off('newMessage');
	},
	methods: {
		flash(message) {
			this.message = message;
			this.show = true;
		},
		hide() {
			setTimeout(() => {
				this.show = false;
			}, 3000);
		}
	}
}