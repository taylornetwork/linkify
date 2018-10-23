<?php

namespace TaylorNetwork\Linkify;

trait MakesLinks
{
	/**
	 * @var Linkify
	 */
	protected $linkify;

	/**
	 * Override Linkify Config
	 *
	 * @param  Linkify $linkify
	 */
	public function linkifyConfig(&$linkify)
	{
		//
	}

	/**
	 * Linkify Custom Parse
	 *
	 * @param  string $caption
	 * @param  string $url
	 * @return  string
	 */
	public function linkifyCustomParse(string $caption, string $url)
	{
		return $url;
	}

	/**
	 * Make the links
	 *
	 * @param  string $text
	 * @return  string
	 */
	public function linkify(string $text) 
	{
		return $this->getLinkifyInstance()->parse($text);
	}

	/**
	 * Get Linkify Instance
	 *
	 * @return  Linkify
	 */
	public function getLinkifyInstance()
	{
		if(!$this->linkify || !$this->linkify instanceof Linkify) {
			$this->linkify = new Linkify;

			$this->linkify->setConfig('customCallback', function ($caption, $url) {
				return $this->linkifyCustomParse($caption, $url);
			});

			$this->linkifyConfig($this->linkify);
		}

		return $this->linkify;
	}
}