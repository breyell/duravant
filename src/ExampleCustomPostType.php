<?php

use Timber\Timber;
use Timber\Post;

/**
 * Class Product
 *
 * Implements custom JSON serialization.
 */
class Product extends Post implements JsonSerializable
{
	/**
	 * Defines data that is used when post is converted to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): mixed
	{
		$image = Timber::get_post($this->image());

		return [
			'id' => $this->id,
			'title' => $this->title(),
			'link' => $this->link(),
			'image' => [
				'src' => $image->src,
				'srcset' => $image->srcset(),
				'alt' => $image->alt,
				'width' => $image->width,
				'height' => $image->height,
			],
		];
	}
}
