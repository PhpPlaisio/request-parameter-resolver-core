<?php
declare(strict_types=1);

namespace Plaisio\RequestParameterResolver\Test;

use Plaisio\PlaisioKernel;
use Plaisio\Request\CoreRequest;
use Plaisio\Request\Request;

/**
 * Kernel for testing purposes.
 */
class TestKernel extends PlaisioKernel
{
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
