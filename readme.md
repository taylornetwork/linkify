# Linkify

A package to convert all links in a string to Markdown, HTML, or your own custom format.

Painlessly Convert:

```php
'This text has a link https://github.com/taylornetwork/linkify and also another one https://google.com'
```

To:

```php
'This text has a link [github.com](https://github.com/taylornetwork/linkify) and also another one [google.com](https://google.com)'
 
// OR 
 
'This text has a link <a href="https://github.com/taylornetwork/linkify">github.com</a> and also another one <a href="https://google.com">google.com</a>'
```

## Install

Via Composer

```bash
$ composer require taylornetwork/linkify
```

## Usage

See [Config Options](#config)

### Linkify Class

**Basic Usage**

```php
use TaylorNetwork\Linkify\Linkify;

$text = 'This has a link. https://google.com';

$linkify = new Linkify;
$linkify->parse($text);
```

Returns

```
'This has a link. [google.com](https://google.com)'
```

**Basic Usage - Static Call**

```php
use TaylorNetwork\Linkify\Linkify;

$text = 'This has a link. https://google.com';

Linkify::instance()->parse($text);
```

Returns

```
'This has a link. [google.com](https://google.com)'
```

**Override Config**

```php
use TaylorNetwork\Linkify\Linkify;

$text = 'This has a link. https://google.com';

$linkify = new Linkify;
$linkify->setConfig('convertTo', Linkify::ConvertHTML);
$linkify->parse($text);
```

Returns

```
'This has a link. <a href="https://google.com">google.com</a>'
```

**Override Config - Static, One Line**

```php
use TaylorNetwork\Linkify\Linkify;

$text = 'This has a link. https://google.com';

Linkify::instance()->setConfig('convertTo', Linkify::ConvertHTML)->parse($text);
```

Returns

```
'This has a link. <a href="https://google.com">google.com</a>'
```

---

### Makes Links Trait

The `MakesLinks` trait will allow you to access the parser with a `linkify($text)` method on your class.

**Basic Usage**

```php
use TaylorNetwork\Linkify\MakesLinks;

class DummyClass
{
	use MakesLinks;
	
	protected $text = 'This has a link. https://google.com';
	
	public function getParsedText()
	{
		return $this->linkify($this->text);
	}
}
```

`getParsedText()` returns

```
'This has a link. [google.com](https://google.com)'
```

**Override Config**

```php
use TaylorNetwork\Linkify\MakesLinks;
use TaylorNetwork\Linkify\Linkify;

class DummyClass
{
	use MakesLinks;
	
	protected $text = 'This has a link. https://google.com';
	
	public function getParsedText()
	{
		return $this->linkify($this->text);
	}
	
	public function linkifyConfig(&$linkify)
	{
		$linkify->setConfig('convertTo', Linkify::ConvertHTML);
		$linkify->setConfig('linkAttributes', [
			'class' => 'btn-link'
		]);
	}
}
```

`getParsedText()` returns

```
'This has a link. <a class="btn-link" href="https://google.com">google.com</a>'
```

**With Custom Format**

```php
use TaylorNetwork\Linkify\MakesLinks;
use TaylorNetwork\Linkify\Linkify;

class DummyClass
{
	use MakesLinks;
	
	protected $text = 'This has a link. https://google.com';
	
	public function getParsedText()
	{
		return $this->linkify($this->text);
	}
	
	public function linkifyConfig(&$linkify)
	{
		$linkify->setConfig('convertTo', Linkify::ConvertCustom);
	}
	
	public function linkifyCustomParse(string $caption, string $url) 
	{
		return '=>' . $caption . '<=#' . $url . '#';
	}
}
```

`getParsedText()` returns

```
'This has a link. =>google.com<=#https://google.com#'
```


## Config

Publish the config using the artisan command.

```bash
$ php artisan vendor:publish --provider="TaylorNetwork\Linkify\LinkifyServiceProvider"
```

Will publish the config file to `config/linkify.php`

---

#### Link Format

You can change the default link format by changing the `convertTo` setting in `config/linkify.php`

```php
'convertTo' => Linkify::ConvertMarkdown, // Converts to markdown links
// OR
'convertTo' => Linkify::ConvertHTML,     // Converts to <a> links
// OR
'convertTo' => Linkify::ConvertCustom,   // Your own custom callback
```
---
#### Link Attributes (HTML Only)

You can add any HTML link attributes you want to include in the `<a>` tag when generating a link (Don't use `href`). 

Add the key and value to the `linkAttributes` array.

```php
'linkAttributes' => [
	'target' => '_blank',
	'class' => 'btn-link',
],
```

Would generate links:
```
<a target="_blank" class="btn-link" href="$url">$caption</a>
``` 
Where `$caption` and `$url` would be replaced automatically.

*Note: attributes are added in the order from the array and this only runs if using the `Linkify::ConvertHTML` setting in `convertTo`*

---

#### Only format non-formatted links

The `checkForExistingFormatting` setting should be set to `true` if you want to only convert links that don't have existing formatting (default).

For example:

```php
// Link is already formatted, by user, or another package, etc.
$text = 'Link: [link](https://google.com)';

Linkify::instance()->parse($text);

// Returns

// If 'checkForExistingFormatting' is true
'Link: [link](https://google.com)'

// If 'checkForExistingFormatting' is false
'Link: [link]([google.com](https://google.com))'
```
---
#### Custom Formatting

**You can define a custom formatter in the config file, but prefer that you use the `MakeLinks` trait.**

[See Makes Links Trait](#makes-links-trait)

```php
'customCallback' => function ($caption, $url) {
	return '{' . $caption . '}#' . $url . '#';
},
```

If `'convertTo' => Linkify::ConvertCustom` the `$caption` and `$url` will be passed to the callback.

This would return: `'{google.com}#https://google.com#'`

## License

MIT
