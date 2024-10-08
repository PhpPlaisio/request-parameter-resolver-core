<?php
declare(strict_types=1);

namespace Plaisio\RequestParameterResolver;

use Plaisio\PlaisioObject;

/**
 * A plain RequestParameterResolver for resolving the URL parameters from a clean URL without any additional
 * functionalities.
 */
class CoreRequestParameterResolver extends PlaisioObject implements RequestParameterResolver
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The parts of the URI.
   *
   * @var string[]
   *
   * @api
   * @since 1.0.0
   */
  protected array $parts;

  /**
   * Special parts of the URI. Map from key to value.
   *
   * @var array
   *
   * @api
   * @since 1.0.0
   */
  protected array $specials = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Resolves the parameters of a clean URL and enhances $_GET accordingly.
   *
   * @return void
   *
   * @api
   * @since 1.0.0
   */
  public function resolveRequestParameters(): void
  {
    $this->partialise();
    $this->handleSpecials();
    $this->enhanceGetKeyValue();
    $this->enhanceGetSpecial();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enhances $_GET with parameters given as key-value pairs in the clean URL.
   *
   * @api
   * @since 1.0.0
   */
  protected function enhanceGetKeyValue(): void
  {
    // Ensure that $this->parts has an even amount of elements.
    if (count($this->parts) % 2!=0) $this->parts[] = '';

    $n = count($this->parts);
    for ($i = 0; $i<$n; $i += 2)
    {
      $_GET[urldecode($this->parts[$i])] = urldecode($this->parts[$i + 1]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enhances $_GET with the special parameters in the clean URL.
   *
   * @api
   * @since 1.0.0
   */
  protected function enhanceGetSpecial(): void
  {
    foreach ($this->specials as $key => $value)
    {
      $_GET[$key] = $value ?? '';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles specials cases of the request URI.
   *
   * A normal request URI has the form /key1/value1/key2/value2/... other cases are special cases.
   *
   * This method handles to following special cases:
   * <ul>
   * <li> /pag_alias/key1/value1/key2/value2/...
   * </ul>
   *
   * @api
   * @since 1.0.0
   */
  protected function handleSpecials(): void
  {
    if (empty($this->parts)) return;

    if ($this->parts[0]!='pag')
    {
      $this->specials['pag_alias'] = urldecode($this->parts[0]);
      array_shift($this->parts);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Splits the request URI into parts using slash as separator.
   *
   * @api
   * @since 1.0.0
   */
  protected function partialise(): void
  {
    $uri = $this->nub->request->requestUri;

    if (str_contains($uri, '?'))
    {
      $uri = strstr($uri, '?', true);
    }

    $uri = trim($uri, '/');
    if ($uri==='')
    {
      $this->parts = [];
    }
    else
    {
      $this->parts = explode('/', $uri);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
