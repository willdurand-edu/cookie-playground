<?php

use Symfony\Component\Yaml\Yaml;

class YamlStorage implements Storage
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;

        if (!is_file($this->filename)) {
            touch($this->filename);
        }
    }

    public function get($id, \DateTime $date = null)
    {
        $data = Yaml::parse(file_get_contents($this->filename));

        if (!isset($data[$id])) {
            throw new \Exception('ID not found');
        }

        $data = $data[$id];

        if (null !== $date) {
            $date = $date->format(\DateTime::ISO8601);

            if (!isset($data[$date])) {
                throw new \Exception(sprintf('No data found for date: %s', $date));
            }

            $data = $data[$date];
        }

        return $data;
    }

    public function set($id, \DateTime $date, array $data)
    {
        $normalizedData = [];
        foreach ($data as $k => $v) {
            $k = ucfirst(str_replace(' ', '', ucwords(strtr(strtolower($k), '_-', '  '))));

            $normalizedData[$k] = $v;
        }

        $data = Yaml::parse(file_get_contents($this->filename));

        $data[$id][$date->format(\DateTime::ISO8601)] = $normalizedData;

        file_put_contents($this->filename, Yaml::dump($data));
    }
}
