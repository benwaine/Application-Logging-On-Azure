<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAutoload()
    {
        Microsoft_AutoLoader::Register();
    }

    protected function _initBlob()
    {
        $this->getResource('autoload');

        $opts = $this->getOption('azure');
        $blob = $opts['blob'];

        $storageClient = new Microsoft_WindowsAzure_Storage_Blob(
                        $blob['host'], $blob['acname'], $blob['paccess']);
        $storageClient->registerStreamWrapper('blob');

        return $storageClient;
    }

    protected function _initLog()
    {
        $opts = $this->getOption('azure');
        $blob = $opts['blob'];

        $storageClient = $this->getResource('blob');

        if(!$storageClient->containerExists($blob['logs']['container']))
        {
            $storageClient->createContainer($blob['logs']['container']);
        }

        if(!$storageClient->blobExists($blob['logs']['container'], $blob['logs']['log']))
        {
            file_put_contents($blob['logs']['stream'], "\n");
        }

        $writer = new Zend_Log_Writer_Stream($blob['logs']['stream']);
        $log = new Zend_Log($writer);

        $log->info('Logging Initialized');

        return $log;
    }

}

