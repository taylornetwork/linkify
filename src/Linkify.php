<?php

namespace TaylorNetwork\Linkify;

class Linkify
{
	const ConvertMarkdown = 1;
	const ConvertHTML = 2;
	const ConvertCustom = 3;

	/**
	 * Linkify Config
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Text to Linkify
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * Found URLs
	 *
	 * @var array
	 */
	protected $found = [];

	/**
	 * Parsed Text
	 *
	 * @var string
	 */
	protected $parsed;

	/**
	 * Linkify Config
	 *
	 * @var array
	 */
	public function __construct()
	{
		$this->config = config('linkify');
	}

	/**
	 * Get a new instance
	 *
	 * @return Linkify
	 */
	public static function instance()
	{
		return new static;
	}

	/**
	 * Get found URLs
	 *
	 * @return array
	 */
	public function getFound()
	{
		return $this->found;
	}

	/**
	 * Get parsed text
	 *
	 * @return string
	 */
	public function getParsed()
	{
		return $this->parsed;
	}

	/**
	 * Override Config
	 *
	 * @param string  $key
	 * @param mixed  $value
	 * @return $this;
	 */
	public function setConfig(string $key, $value)
	{
		$this->config[$key] = $value;
		return $this;
	}

	/**
	 * Convert to markdown
	 *
	 * @param string  $caption
	 * @param string  $url
	 * @return string
	 */
	public function markdown(string $caption, string $url)
	{
		if($this->config['checkForExistingFormatting']) {
			$pos = strpos($this->text, $url);

	        if($this->text[$pos - 2] === ']' && $this->text[$pos - 1] === '(' && $this->text[$pos + strlen($url)] === ')') {
	            return $url; 
	        }
		}
		
		return '[' . $caption . '](' . $url . ')';
	}

	/**
	 * Convert to HTML
	 *
	 * @param string  $caption
	 * @param string  $url
	 * @return string
	 */
	public function HTML(string $caption, string $url)
	{
		if($this->config['checkForExistingFormatting']) {
			$pos = strpos($this->text, $url);

	        if($this->text[$pos - 1] === '>' && substr($this->text, $pos + strlen($url), 4) === '</a>') {
	            return $url; 
	        }
		}

		$attributes = [ '' ];
		foreach($this->config['linkAttributes'] as $key => $value) {
			$attributes[] = $key . '="' . $value . '"';
		}

		return '<a' . implode(' ', $attributes) . ' href="' . $url . '">' . $caption . '</a>';
	}

	/**
	 * Convert using a custom callback
	 *
	 * @param string  $caption
	 * @param string  $url
	 * @return string
	 */
	public function custom(string $caption, string $url)
	{
		if($this->config['customCallback']) {
			$callback = $this->config['customCallback'];
			return $callback($caption, $url);
		}
		return $url;
	}

	/**
	 * Parse text
	 *
	 * @param string  $text
	 * @return string
	 */
   	public function parse(string $text)
   	{
   		$this->text = $text;

        $callback = function ($urlMatch)
        {
            $url = $urlMatch[0];

            $this->found[] = $url;

            // Look for protocol
            preg_match('~^(ht|f)tps?://~', $url, $protocolMatch);

            if($protocolMatch)
            {
                $protocol = $protocolMatch[0];
            }
            else
            {
                $protocol = 'http://';
                $url = $protocol . $url;
            }

            // Start building caption, remove protocol from url
            $noProtocol = substr($url, strlen($protocol));

            // Check for a variation of www
            preg_match('/www\d{0,3}\./', $noProtocol, $wwwMatch);

            if($wwwMatch)
            {
                // Remove www
                $noProtocol = substr($noProtocol, strlen($wwwMatch[0]));
            }

            // Only use domain name as caption
            $caption = explode('/', $noProtocol)[0];

            switch($this->config['convertTo']) {
            	case static::ConvertMarkdown:
            		$parsed = $this->markdown($caption, $url);
            		break;

            	case static::ConvertHTML:
            		$parsed = $this->HTML($caption, $url);
            		break;

            	case static::ConvertCustom:
            		$parsed = $this->custom($caption, $url);
            		break;

            	default: 
            		$parsed = $url;
            		break;
            }

            return $parsed;
        };

        $this->parsed = preg_replace_callback($this->config['pattern'], $callback, $this->text);

        return $this->parsed;
   	}
}