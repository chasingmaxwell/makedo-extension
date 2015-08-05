<?php

namespace chasingmaxwell\MakeDoExtension;

use chasingmaxwell\MakeDo\MakeDo;
use Behat\Behat\Context\Context;

/**
 * MakeDo aware context.
 */
interface MakeDoAwareContext extends Context {
  public function setMakeDo(MakeDo $makeDo);
}
