<?php


namespace App\EventListener;
 
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
 
class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->set('Strict-Transport-Security', 'max-age=7776000');
        $event->getResponse()->headers->set('Content-Security-Policy', "default-src 'self' https://netdna.bootstrapcdn.com https://stackpath.bootstrapcdn.com https://*.nyu.edu https://*.nyumc.org https://fonts.googleapis.com; script-src 'self' https://cdnjs.cloudflare.com https://www.googletagmanager.com https://script.crazyegg.com https://ajax.googleapis.com https://stackpath.bootstrapcdn.com; object-src 'none'; frame-ancestors 'self'; form-action 'self'");
        $event->getResponse()->headers->set('X-Content-Type-Options', 'nosniff');
    }
}

?>
