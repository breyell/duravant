<?php

use Timber\Timber;
use Timber\Post;

/**
 * Class Brand
 *
 * Implements custom JSON serialization.
 */
class Brand extends Post implements JsonSerializable
{
	/**
	 * Defines data that is used when post is converted to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): mixed
	{
		$image = Timber::get_post($this->logo());

		return [
			'id' => $this->id,
			'title' => $this->title(),
			'link' => $this->link(),
			'logo' => [
				'src' => $image->src,
				'srcset' => $image->srcset(),
				'alt' => $image->alt,
				'width' => $image->width,
				'height' => $image->height,
			],
			'previewHeading' => $this->preview_heading,
			'previewBody' => $this->preview_body,
			'productCategories' => array_column($this->terms('product-category'), 'id'),
		];
	}
}
