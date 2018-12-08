<?php
namespace app\demo\controller;

use think\Controller;

/**
 *
 */
class Index extends Controller
{

  public function index()
  {
    # code...
    return $this->fetch();
  }
}
