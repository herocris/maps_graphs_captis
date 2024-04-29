<?php
  function setActiveRoute($name)
  {
    return Request::is($name)?'active':'';
  }

  function setActiveMenu($name)
  {
    return Request::is($name)?'active bg-info text-white':'';
  }
