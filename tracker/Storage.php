<?php

interface Storage
{
    public function get($id, \DateTime $date = null);

    public function set($id, \DateTime $date, array $data);
}
