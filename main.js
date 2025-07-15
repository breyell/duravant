import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
// import resize from '@alpinejs/resize'
// import collapse from '@alpinejs/collapse'

import MuxPlayer from "@mux/mux-player";
import autoAnimate from '@formkit/auto-animate'

// import gaLink from './resources/js/alpine/ga-link.js'
import countUp from './resources/js/alpine/count-up.js'

Alpine.plugin([focus])

window.Alpine = Alpine

Alpine.data('countUp', countUp)

Alpine.directive('auto-animate', (el, { expression }, { evaluate }) => {
	autoAnimate(el, (expression && evaluate(expression)) || {})
})

Alpine.start()
