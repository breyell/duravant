import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
// import resize from '@alpinejs/resize'
// import collapse from '@alpinejs/collapse'

import MuxPlayer from "@mux/mux-player";
import autoAnimate from '@formkit/auto-animate'

import video from './resources/js/alpine/video.js'
import brandFilter from './resources/js/alpine/brand-filter.js'

Alpine.plugin([focus])

window.Alpine = Alpine

Alpine.data('video', video)
Alpine.data('brandFilter', brandFilter)

Alpine.directive('auto-animate', (el, { expression }, { evaluate }) => {
	autoAnimate(el, (expression && evaluate(expression)) || {})
})

Alpine.start()
