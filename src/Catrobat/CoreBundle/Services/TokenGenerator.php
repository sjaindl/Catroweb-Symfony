<?php
namespace Catrobat\CoreBundle\Services;

class TokenGenerator
{
  
  function __construct()
  {
  }
  
  function generateToken()
  {
    return uniqid(rand(),false);
  }
  
}
