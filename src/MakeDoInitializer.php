<?php

namespace chasingmaxwell\MakeDoExtension;

use chasingmaxwell\MakeDo\MakeDo;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\Context\Context;

/**
 * Initializes MakeDo aware contexts.
 */
class MakeDoInitializer implements ContextInitializer {

  private $makeDo;

  /**
   * Construct an instance of this class.
   */
  public function __construct(MakeDo $makeDo) {
    $this->makeDo = $makeDo;
  }

  /**
   * {@inheritdoc}
   */
  public function initializeContext(Context $context) {
    if (!$context instanceof MakeDoAwareContext) {
      return;
    }

    $context->setMakeDo($this->makeDo);
  }
}
