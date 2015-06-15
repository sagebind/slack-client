<?php

class RoboFile extends \Robo\Tasks
{
    public function test()
    {
        $this->taskPHPUnit()
            ->configFile('phpunit.xml.dist')
            ->run();
    }

    public function docsGenerate()
    {
        $this->taskExec('vendor/bin/apigen')
            ->arg('generate')
            ->option('source', 'src')
            ->option('destination', 'api')
            ->option('template-theme', 'bootstrap')
            ->run();
    }

    public function docsServe()
    {
        $this->taskServer(8000)
            ->dir('api')
            ->run();
    }

    public function docsPublish($message = 'Regenerate docs')
    {
        $this->taskGitStack()
            ->stopOnFail()
            ->checkout('gh-pages api/**')
            ->checkout('gh-pages')
            ->add('api/**')
            ->commit($message)
            ->push('origin', 'gh-pages')
            ->checkout('master')
            ->run();
    }
}
