export default function ($brands) {
	return {
		brands: $brands,
		selectedProductCategory: [],
		selectedSolution: [],
		currentPage: 1,
		perPage: 4,
		width: undefined,
		init() {
			this.$watch('width', () => {
				this.perPage = this.width < 768 ? 4 : 12
				if (this.width < 768) {
					this.perPage = 4
				} else if (this.width > 1023) {
					this.perPage = 12
				} else {
					this.perPage = 9
				}
			})
		},
		get totalPages() {
			return Math.ceil(this.filteredBrands.length / this.perPage)
		},
		get filteredBrands() {
			let filteredBrands = this.brands;

			if (this.selectedProductCategory.length) {
				filteredBrands = filteredBrands.filter((brand) => {
					return brand.productCategories.includes(parseInt(this.selectedProductCategory))
				})
			}

			if (this.selectedSolution.length) {
				filteredBrands = filteredBrands.filter((brand) => {
					return brand.solutions.includes(parseInt(this.selectedSolution))
				})
			}

			return filteredBrands
		},
		get displayedBrands() {
			return this.filteredBrands.slice(
				(this.currentPage - 1) * this.perPage,
				this.currentPage * this.perPage
			)
		},
	}
}
