<?php

namespace chasingmaxwell\MakeDoExtension;

use chasingmaxwell\MakeDo\MakeDo;

abstract class MakeDoRawContext implements MakeDoAwareContext {

  private $makeDo;

  public function setMakeDo(MakeDo $makeDo) {
    $this->makeDo = $makeDo;
  }

  public function getMakeDo() {
    return $this->makeDo;
  }
}
