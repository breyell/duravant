import Alpine from 'alpinejs'
// import focus from '@alpinejs/focus'
// import resize from '@alpinejs/resize'
// import collapse from '@alpinejs/collapse'

import autoAnimate from '@formkit/auto-animate'

// import gaLink from './resources/js/alpine/ga-link.js'

// Alpine.plugin([focus, resize, collapse])

window.Alpine = Alpine

// Alpine.data('gaLink', gaLink)

Alpine.directive('auto-animate', (el, { expression }, { evaluate }) => {
	autoAnimate(el, (expression && evaluate(expression)) || {})
})

Alpine.start()
