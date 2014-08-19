<?php

use Symfony\Component\HttpFoundation\Response;

class GifResponse extends Response
{
    public function __construct()
    {
        parent::__construct();

        $this->setPrivate();

        $this->setContent("\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b");

        $this->headers->set('Content-Type', 'image/gif');
        $this->headers->set('X-Content-Type-Options', 'nosniff');
    }
}
