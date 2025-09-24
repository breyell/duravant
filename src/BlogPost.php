<?php

use Timber\Timber;
use Timber\Post;

/**
 * Class BlogPost
 *
 * Implements custom JSON serialization.
 */
class BlogPost extends Post implements JsonSerializable
{
	/**
	 * Defines data that is used when post is converted to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): mixed
	{
		$image = Timber::get_post($this->blog_hero_image());

		return [
			'id' => $this->id,
			'title' => $this->title(),
			'link' => $this->link(),
			'date' => $this->date(),
			'preview' => $this->article_preview(),
			'image' => [
				'src' => $image->src,
				'srcset' => $image->srcset(),
				'alt' => $image->alt,
				'width' => $image->width,
				'height' => $image->height,
			],
			'blogCategories' => array_column($this->terms('blog-category'), 'slug'),
		];
	}
}
