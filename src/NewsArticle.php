<?php

use Timber\Timber;
use Timber\Post;

/**
 * Class NewsArticle
 *
 * Implements custom JSON serialization.
 */
class NewsArticle extends Post implements JsonSerializable
{
	/**
	 * Defines data that is used when post is converted to JSON.
	 *
	 * @return array
	 */
	public function jsonSerialize(): mixed
	{
		// $image = Timber::get_post($this->logo());

		return [
			'id' => $this->id,
			'title' => $this->title(),
			'link' => $this->link(),
			'date' => $this->date(),
			'preview' => $this->article_preview(),
		];
	}
}
