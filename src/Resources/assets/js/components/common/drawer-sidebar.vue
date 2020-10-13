<template>
	<section>
		<aside class="sidebar" style="transform:translate3d(-100%, 0, 0); left:0;" ref="element">
			<slot></slot>
		</aside>

		<div class="overlay" ref="overlay" @click="close" @transitionend="handleZindex($event)"></div>
	</section>
</template>

<script>
	export default {
        name: 'drawer-sidebar',
        
		data () {
			return {
				speed: '0.3s',

				translate: null,

				active: false
			}
		},

		methods: {
			handleZindex () {
				let opacity = window.getComputedStyle(this.$refs.overlay).getPropertyValue('opacity');

				if (opacity <= 0) {
					this.$refs.overlay.style.zIndex = -999;			
				}
			},

			overlayOpacity (opacity) {
				this.$refs.overlay.style.opacity = opacity;

				if (opacity > 0) {
					this.$refs.overlay.style.zIndex = 999;				
				}
			},

			open () {
				this.translate = 0;

				this.$refs.element.style.transform = 'translate3d(' + this.translate + ', 0 ,0)';
				this.$refs.element.style.transitionDuration = this.speed;

				this.overlayOpacity(1);

				this.$refs.element.classList.add('active');	

				this.active = true;	
			},

			close () {
				this.translate = '-' + this.$refs.element.offsetWidth + 'px';

				this.$refs.element.style.transform = 'translate3d(' + this.translate + ', 0, 0)';	
				this.$refs.element.style.transitionDuration = this.speed;	

				this.overlayOpacity(0);

				this.$refs.element.classList.remove('active');	

				this.active = false;
			}
		}
	}	
</script>

<style scoped lang="scss">
	.overlay {
	    position: fixed;
	    z-index: -999;
	    left: 0px;
	    top: 0px;
	    background: rgba(11, 10, 12, 0.35);
	    opacity: 0;
	    width: 100%;
	    height: 100%;
	    transition: opacity 0.3s ease;
	}

	.sidebar {
	    z-index: 9999;
	    position: fixed;
	    will-change: transform;
	    height: 100%;
	    top: 0px;
		transition: transform 0.3s ease;
	    background: #fff;
	    width: 300px;
		overflow-y: auto;
		overflow-x: hidden;
		word-wrap: break-word;
	}
</style>