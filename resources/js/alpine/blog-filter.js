export default function (articles, blogCategories) {
	return {
		articles: articles,
		blogCategories: blogCategories,
		selectedBlogCategory: '',
		currentPage: 1,
		perPage: 12,
		queryParams: new URLSearchParams(window.location.search),
		start() {
			// Set query params to variables
			if (this.queryParams.get('category')) {
				this.selectedBlogCategory = this.queryParams.get('category')
			}

			if (this.queryParams.get('pg')) {
				if (parseInt(this.queryParams.get('pg')) > this.totalPages) {
					this.currentPage = this.totalPages
					this.updateUrl()
				} else {
					this.currentPage = parseInt(this.queryParams.get('pg'))
				}
			}

			// Watch filter options and upadte url on change
			this.$watch('selectedBlogCategory', () => {
				// dataLayer.push({
				// 	'event': 'filter_click',
				// 	'blog_category': difference,
				// })
				this.updateUrl()
				this.currentPage = 1
			})
			this.$watch('currentPage', () => this.updateUrl())
		},
		categoryBySlug(slug) {
			if (blogCategories.isArray) {
				return blogCategories.find((category) => slug === category.slug).name
			}

			if (typeof blogCategories == 'object') {
				for (const category of Object.values(blogCategories)) {
					if (category.slug === slug) {
						return category.name
					}
				}
			}
		},
		get totalPages() {
			return Math.ceil(this.filteredArticles.length / this.perPage)
		},
		get filteredArticles() {
			let filteredArticles = this.articles;

			if (this.selectedBlogCategory) {
				filteredArticles = filteredArticles.filter((article) => {
					return article.blogCategories.includes(this.selectedBlogCategory)
				})
			}

			return filteredArticles
		},
		get displayedArticles() {
			return this.filteredArticles.slice(
				(this.currentPage - 1) * this.perPage,
				this.currentPage * this.perPage
			)
		},
		updateUrl() {
			this.$nextTick(() => {
				if (this.selectedBlogCategory) {
					this.queryParams.set('category', this.selectedBlogCategory)
				} else {
					this.queryParams.delete('category')
				}

				if (this.currentPage != 1) {
					this.queryParams.set('pg', this.currentPage)
				} else {
					this.queryParams.delete('pg')
				}

				if (this.queryParams.size === 0) {
					window.history.replaceState(
						{},
						'',
						window.location.protocol.concat(
							'//',
							window.location.host,
							window.location.pathname
						)
					)
				} else {
					window.history.replaceState({}, '', `?${this.queryParams.toString()}`)
				}
			})
		},
		getUrl(newPage) {
			let tempQueryParams = this.queryParams

			if (newPage != 1) {
				this.queryParams.set('pg', newPage)
			} else {
				this.queryParams.delete('pg')
			}

			if (tempQueryParams.size === 0) {
				return window.location.protocol.concat(
					'//',
					window.location.host,
					window.location.pathname
				)
			}

			return '?' + this.queryParams.toString()
		}
	}
}
