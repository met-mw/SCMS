<?php
namespace App\Classes;


class Configurator
{

    /** @var array */
    protected $projectConfig;

    public function __construct(array $projectConfig)
    {
        $this->setProjectConfig($projectConfig);
    }

    public function getProjectConfig()
    {
        return $this->projectConfig;
    }

    public function getProjectConfigAsRows()
    {
        $configAsRows = [];
        foreach ($this->getProjectConfig() as $name => $data) {
            $configAsRows[] = ['name' => $name, 'data' => $data];
        }

        return $configAsRows;
    }

    public function setProjectConfig(array $projectConfig)
    {
        $this->projectConfig = $projectConfig;
    }

    public function hasParam($paramName)
    {
        return isset($this->getProjectConfig()[$paramName]);
    }

    public function setParam($paramName, $value)
    {
        $this->projectConfig[$paramName] = $value;
    }

    public function getParam($paramName)
    {
        return $this->hasParam($paramName) ? $this->getProjectConfig()[$paramName] : null;
    }

}