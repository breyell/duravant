export default function (eventName) {
	return {
		init() {
			const link = this.$el.getAttribute('href')
			const target = this.$el.getAttribute('target')

			if (target === '_blank') {
				this.$el.addEventListener('click', (event) => {
					dataLayer.push({
						'event': eventName,
					})
				})
			} else {
				this.$el.addEventListener('click', (event) => {
					event.preventDefault()
					dataLayer.push({
						'event': eventName,
						'eventCallback': function () {
							window.location.href = link
						}
					})

					setTimeout(function () {
						window.location.href = link
					}, 1000)
				})
			}
		},
	}
}
