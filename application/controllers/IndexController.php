<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $storageClient = $bootstrap->getResource('blob'); /** @var $storageClient Microsoft_WindowsAzure_Storage_Blob **/

        $config = $bootstrap->getOption('azure');
        $project = $bootstrap->getOption('project');
        $fileStr =  file_get_contents($config['blob']['logs']['stream']);
        $logAr = explode("\n", $fileStr);

        $this->view->projectName = $project['name'];
        $this->view->logs = $logAr;


        if($this->_request->getParam('ajax', false))
        {
            $this->render('ajax');
        }
        else
        {
            $this->render();
        }
    }



}

