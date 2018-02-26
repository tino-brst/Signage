<template>
	<div class="timelineItem vue-component">
		<img :src="item.location">
		<button @click="$emit('delete')"> delete </button>
	</div>
</template>

<script>
export default {
	props: {
		item: {
			type: Object,
			default: () => { return {} }
		}
	},
	data() {
		return {
			time: 5
		}
	}
}
</script>

<style>
	.timelineItem {
		display: flex;
		flex-direction: column;
		justify-items: flex-end;
		padding: 0.5rem;
	}
	button {
		margin-left: auto;
	} 
</style>
