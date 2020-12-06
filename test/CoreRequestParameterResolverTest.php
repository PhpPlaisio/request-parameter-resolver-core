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
   * The kernel for testing purposes.
   *
   * @var TestKernel
   */
  private TestKernel $kernel;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function setUp(): void
  {
    $this->kernel = new TestKernel();

    $_GET = [];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing value.
   */
  public function testMissingEmptyValue1(): void
  {
    $params = ['pag' => '123', 'key1' => '', 'key2' => 'value2'];

    $_SERVER['REQUEST_URI'] = '/pag/123/key1//key2/value2';

    $resolver = new CoreRequestParameterResolver($this->kernel);
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
    $this->assertSame('', $_GET['key1']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing value.
   */
  public function testMissingValue1(): void
  {
    $params = ['pag' => '123', 'key' => ''];

    $_SERVER['REQUEST_URI'] = '/pag/123/key';

    $resolver = new CoreRequestParameterResolver($this->kernel);
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
    $this->assertSame('', $_GET['key']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing value.
   */
  public function testMissingValue2(): void
  {
    $params = ['pag' => '123', 'key' => ''];

    $_SERVER['REQUEST_URI'] = '/pag/123/key/';

    $resolver = new CoreRequestParameterResolver($this->kernel);
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
    $this->assertSame('', $_GET['key']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test parameters are urldecoded.
   */
  public function testRequestDecoding1(): void
  {
    $params = ['pag' => '123', 'redirect' => '/', 'bull' => '!@#$%^&*(); &amp;'];

    $_SERVER['REQUEST_URI'] = '/pag/123/bull/'.urlencode('!@#$%^&*(); &amp;').'/redirect'.'/'.urlencode('/');

    $resolver = new CoreRequestParameterResolver($this->kernel);
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test / is requested.
   */
  public function testRequestHome1(): void
  {
    $_SERVER['REQUEST_URI'] = '/';

    $resolver = new CoreRequestParameterResolver($this->kernel);
    $resolver->resolveRequestParameters();

    $this->assertSame([], $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test / is requested with CGI parameters.
   */
  public function testRequestHome2(): void
  {
    $params = ['a' => '1', 'b' => '2'];

    $_SERVER['REQUEST_URI'] = '/';
    $_GET                   = $params;

    $resolver = new CoreRequestParameterResolver($this->kernel);
    $resolver->resolveRequestParameters();

    $this->assertSame($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test / is requested with CGI parameters.
   */
  public function testRequestHome3(): void
  {
    $params = ['a' => '1', 'b' => '2'];

    $_SERVER['REQUEST_URI'] = '/?a=1;b=2';
    $_GET                   = $params;

    $resolver = new CoreRequestParameterResolver($this->kernel);
    $resolver->resolveRequestParameters();

    $this->assertSame($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test /login is requested with CGI parameter ending with /.
   */
  public function testRequestHome4(): void
  {
    $params = ['pag_alias' => 'login', 'redirect' => '/'];

    $_SERVER['REQUEST_URI'] = '/login/redirect'.'/'.urlencode('/');

    $resolver = new CoreRequestParameterResolver($this->kernel);
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test page with (obfuscated) ID 123 is requested with CGI parameter ending with /.
   */
  public function testRequestPage1(): void
  {
    $params = ['pag' => '123', 'redirect' => '/'];

    $_SERVER['REQUEST_URI'] = '/pag/123/redirect'.'/'.urlencode('/');

    $resolver = new CoreRequestParameterResolver($this->kernel);
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
