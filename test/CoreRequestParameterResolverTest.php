<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\RequestParamterResolver;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\RequestParameterResolver\CoreRequestParameterResolver;

//----------------------------------------------------------------------------------------------------------------------

/**
 * Test cases for CoreRequestParameterResolverTest.
 */
class CoreRequestParameterResolverTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Ensures $_GET and $_SERVER are empty arrays.
   */
  public function setUp()
  {
    parent::setUp();

    $_GET    = [];
    $_SERVER = [];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing value.
   */
  public function testMissingEmptyValue1()
  {
    $params = ['pag' => '123', 'key1' => '', 'key2' => 'value2'];

    $_SERVER['REQUEST_URI'] = '/pag/123/key1//key2/value2';

    $resolver = new CoreRequestParameterResolver();
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
    $this->assertSame('', $_GET['key1']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing value.
   */
  public function testMissingValue1()
  {
    $params = ['pag' => '123', 'key' => ''];

    $_SERVER['REQUEST_URI'] = '/pag/123/key';

    $resolver = new CoreRequestParameterResolver();
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
    $this->assertSame('', $_GET['key']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with missing value.
   */
  public function testMissingValue2()
  {
    $params = ['pag' => '123', 'key' => ''];

    $_SERVER['REQUEST_URI'] = '/pag/123/key/';

    $resolver = new CoreRequestParameterResolver();
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
    $this->assertSame('', $_GET['key']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test parameters are urldecoded.
   */
  public function testRequestDecoding1()
  {
    $params = ['pag' => '123', 'redirect' => '/', 'bull' => '!@#$%^&*(); &amp;'];

    $_SERVER['REQUEST_URI'] = '/pag/123/bull/'.urlencode('!@#$%^&*(); &amp;').'/redirect'.'/'.urlencode('/');

    $resolver = new CoreRequestParameterResolver();
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test / is requested.
   */
  public function testRequestHome1()
  {
    $_SERVER['REQUEST_URI'] = '/';

    $resolver = new CoreRequestParameterResolver();
    $resolver->resolveRequestParameters();

    $this->assertSame([], $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test / is requested with CGI parameters.
   */
  public function testRequestHome2()
  {
    $params = ['a' => '1', 'b' => '2'];

    $_SERVER['REQUEST_URI'] = '/';
    $_GET                   = $params;

    $resolver = new CoreRequestParameterResolver();
    $resolver->resolveRequestParameters();

    $this->assertSame($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test / is requested with CGI parameters.
   */
  public function testRequestHome3()
  {
    $params = ['a' => '1', 'b' => '2'];

    $_SERVER['REQUEST_URI'] = '/?a=1;b=2';
    $_GET                   = $params;

    $resolver = new CoreRequestParameterResolver();
    $resolver->resolveRequestParameters();

    $this->assertSame($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test /login is requested with CGI parameter ending with /.
   */
  public function testRequestHome4()
  {
    $params = ['pag_alias' => 'login', 'redirect' => '/'];

    $_SERVER['REQUEST_URI'] = '/login/redirect'.'/'.urlencode('/');

    $resolver = new CoreRequestParameterResolver();
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test page with (obfuscated) ID 123 is requested with CGI parameter ending with /.
   */
  public function testRequestPage1()
  {
    $params = ['pag' => '123', 'redirect' => '/'];

    $_SERVER['REQUEST_URI'] = '/pag/123/redirect'.'/'.urlencode('/');

    $resolver = new CoreRequestParameterResolver();
    $resolver->resolveRequestParameters();

    $this->assertEquals($params, $_GET);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------