<?php
declare(strict_types=1);

namespace Plaisio\RequestParameterResolver\Test;

use PHPUnit\Framework\TestCase;
use Plaisio\RequestParameterResolver\CoreRequestParameterResolver;

/**
 * Test cases for CoreRequestParameterResolverTest.
 */
class CoreRequestParameterResolverTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing value.
   */
  public function testMissingEmptyValue1(): void
  {
    $resolver = new CoreRequestParameterResolver();
    $get      = $resolver->resolveRequestParameters('/pag/123/key1//key2/value2');

    $this->assertEquals(['pag' => '123', 'key1' => '', 'key2' => 'value2'], $get);
    $this->assertSame('', $get['key1']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing value.
   */
  public function testMissingValue1(): void
  {
    $resolver = new CoreRequestParameterResolver();
    $get      = $resolver->resolveRequestParameters('/pag/123/key');

    $this->assertEquals(['pag' => '123', 'key' => ''], $get);
    $this->assertSame('', $get['key']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing value.
   */
  public function testMissingValue2(): void
  {
    $resolver = new CoreRequestParameterResolver();
    $get      = $resolver->resolveRequestParameters('/pag/123/key/');

    $this->assertEquals(['pag' => '123', 'key' => ''], $get);
    $this->assertSame('', $get['key']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test parameters are urldecoded.
   */
  public function testRequestDecoding1(): void
  {
    $requestUri = '/pag/123/bull/'.urlencode('!@#$%^&*(); &amp;').'/redirect/'.urlencode('/');
    $resolver   = new CoreRequestParameterResolver();
    $get        = $resolver->resolveRequestParameters($requestUri);

    $this->assertEquals(['pag' => '123', 'redirect' => '/', 'bull' => '!@#$%^&*(); &amp;'], $get);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test / is requested.
   */
  public function testRequestHome1(): void
  {
    $resolver = new CoreRequestParameterResolver();
    $get      = $resolver->resolveRequestParameters('/');

    $this->assertSame([], $get);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test / is requested with CGI parameters.
   */
  public function testRequestHome3(): void
  {
    $resolver = new CoreRequestParameterResolver();
    $get      = $resolver->resolveRequestParameters('/?a=1;b=2');

    $this->assertSame([], $get);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test /login is requested with CGI parameter ending with /.
   */
  public function testRequestHome4(): void
  {
    $resolver = new CoreRequestParameterResolver();
    $get      = $resolver->resolveRequestParameters('/login/redirect/'.urlencode('/'));

    $this->assertEquals(['pag_alias' => 'login', 'redirect' => '/'], $get);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test page with (obfuscated) ID 123 is requested with CGI parameter ending with /.
   */
  public function testRequestPage1(): void
  {
    $resolver = new CoreRequestParameterResolver();
    $get      = $resolver->resolveRequestParameters('/pag/123/redirect/'.urlencode('/'));

    $this->assertEquals(['pag' => '123', 'redirect' => '/'], $get);
  }

  //--------------------------------------------------------------------------------------------------------------------
}


//----------------------------------------------------------------------------------------------------------------------
