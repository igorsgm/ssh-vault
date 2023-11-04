<?php

namespace App\SshConfig;

class SshConfig
{
    private SshConfigParser $parser;

    private array $hosts = [];

    public function __construct()
    {
        $this->parser = new SshConfigParser();
    }

    /**
     * Load hosts from ssh config file
     */
    public function load()
    {
        $configFilePath = $this->configFilePath();
        $this->hosts = file_exists($configFilePath) ? $this->parser->parse($this->content()) : [];

        return $this->hosts();
    }

    /**
     * Write new hosts to ssh config file
     */
    public function sync()
    {
        $result = '';
        foreach ($this->hosts() as $host) {
            $result .= $host;
            $result .= "\n";
        }

        file_put_contents($this->configFilePath(), $result);
    }

    /**
     * Add new host to ssh config
     */
    public function add($host)
    {
        $this->hosts[] = $host;
    }

    /**
     * Remove host by index
     */
    public function remove($index)
    {
        unset($this->hosts[$index]);
    }

    /**
     * Get hosts
     */
    public function hosts()
    {
        return $this->hosts;
    }

    /**
     * Check if ssh config is empty
     */
    public function isEmpty()
    {
        return count($this->hosts()) == 0;
    }

    /**
     * Get the content of the config file
     */
    public function content(): string
    {
        return file_get_contents($this->configFilePath());
    }

    private function configFilePath()
    {
        return $this->homeFolderPath().'/.ssh/config';
    }

    private function homeFolderPath()
    {
        return rtrim(getenv('HOME'));
    }
}
