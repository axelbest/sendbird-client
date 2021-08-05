<?php

namespace Axelbest\SendbirdClient;

use Axelbest\SendbirdClient\DependencyInjection\SendbirdBundleExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SendbirdBundle extends Bundle
{

    public function getContainerExtension(): Extension
    {
        if (null === $this->extension) {
            $this->extension = new SendbirdBundleExtension();
        }

        return $this->extension;
    }
}