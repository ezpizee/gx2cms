<?php

namespace GX2CMSJoomla\ContextProcessors\AuthenticatedUser;

use GX2CMSJoomla\ContextProcessors\BaseContextProcessor;

class ContextProcessor extends BaseContextProcessor
{
  protected function requiredAccessToken(): bool {return false;}

  protected function allowedMethods(): array {return ['GET'];}

  protected function validRequiredParams(): bool {return true;}

  public function processContext(): void {
      $res = $this->microserviceClient->get('/api/user/me');
      $this->setContextData($res->get('data'));
  }
}
