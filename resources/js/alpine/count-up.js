import { CountUp } from 'countup.js';

export default function (number, prefix, suffix) {
	return {
		init() {
			const countUp = new CountUp(this.$el, number, {
				prefix: prefix,
				suffix: suffix,
			})
			countUp.start()
		},
	}
}
