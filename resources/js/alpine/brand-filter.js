export default function ($brands) {
	return {
		brands: $brands,
		// selectedSolution: [],
		selectedProductCategory: [],
		currentPage: 1,
		perPage: 8,
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

			// if (this.selectedMarkets.length) {
			// 	const selectedMarketsSet = new Set(this.selectedMarkets)
			// 	filteredBrands = filteredBrands.filter((product) => {
			// 		const set1 = new Set(product.markets)
			// 		return !!set1.intersection(selectedMarketsSet).size
			// 	})
			// }

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
