<?php
declare(strict_types=1);

namespace Plaisio\RequestParameterResolver;

/**
 * A plain RequestParameterResolver for resolving the URL parameters from a clean URL without any additional
 * functionalities.
 */
class CoreRequestParameterResolver implements RequestParameterResolver
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The CGI variables extracted from the clean URL.
   *
   * @var array
   */
  private array $cgi = [];

  /**
   * The parts of the URI.
   *
   * @var string[]
   */
  private array $parts;

  /**
   * Special parts of the URI. Map from key to value.
   *
   * @var array
   */
  private array $specials = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Resolves the parameters of a clean URL and enhances $this->get accordingly.
   *
   * @api
   * @since 2.0.0
   */
  public function resolveRequestParameters(string $requestUri): array
  {
    $this->partialise($requestUri);
    $this->handleSpecials();
    $this->enhanceGetKeyValue();
    $this->enhanceGetSpecial();

    return $this->cgi;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enhances $this->get with parameters given as key-value pairs in the clean URL.
   *
   * @api
   * @since 1.0.0
   */
  protected function enhanceGetKeyValue(): void
  {
    // Ensure that $this->parts has an even number of elements.
    if (count($this->parts) % 2!=0)
    {
      $this->parts[] = '';
    }

    $n = count($this->parts);
    for ($i = 0; $i<$n; $i += 2)
    {
      $this->cgi[urldecode($this->parts[$i])] = urldecode($this->parts[$i + 1]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Enhances $this->get with the special parameters in the clean URL.
   *
   * @api
   * @since 1.0.0
   */
  protected function enhanceGetSpecial(): void
  {
    foreach ($this->specials as $key => $value)
    {
      $this->cgi[$key] = $value ?? '';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles special cases of the request URI.
   *
   * A normal request URI has the form /key1/value1/key2/value2/... other cases are special cases.
   *
   * This method handles the following special cases:
   * <ul>
   * <li> /pag_alias/key1/value1/key2/value2/...
   * </ul>
   *
   * @api
   * @since 1.0.0
   */
  protected function handleSpecials(): void
  {
    if (empty($this->parts))
    {
      return;
    }

    if ($this->parts[0]!=='pag')
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
  protected function partialise(string $requestUri): void
  {
    if (str_contains($requestUri, '?'))
    {
      $requestUri = strstr($requestUri, '?', true);
    }

    $requestUri = trim($requestUri, '/');
    if ($requestUri==='')
    {
      $this->parts = [];
    }
    else
    {
      $this->parts = explode('/', $requestUri);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
