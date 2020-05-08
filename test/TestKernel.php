<?php
declare(strict_types=1);

namespace Plaisio\RequestParameterResolver\Test;

use Plaisio\Kernel\Nub;
use Plaisio\Request\CoreRequest;
use Plaisio\Request\Request;

/**
 * Kernel for testing purposes.
 */
class TestKernel extends Nub
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the helper object for providing information about the HTTP request.
   *
   * @return Request
   */
  protected function getRequest(): Request
  {
    return new CoreRequest();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
