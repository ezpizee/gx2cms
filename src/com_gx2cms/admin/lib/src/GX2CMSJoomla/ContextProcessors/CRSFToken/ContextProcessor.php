<?php

namespace GX2CMSJoomla\ContextProcessors\CRSFToken;

use GX2CMSJoomla\ContextProcessors\BaseContextProcessor;
use Joomla\CMS\Session\Session;

class ContextProcessor extends BaseContextProcessor
{
  protected function requiredAccessToken(): bool {return false;}

  protected function allowedMethods(): array {return ['GET'];}

  protected function validRequiredParams(): bool {return true;}

  public function processContext(): void {$this->setContext(['token' => Session::getFormToken()]);}
}
