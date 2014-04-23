<?php

namespace Balraj\ProcessXML;

class User {

    private $accountId;
    private $response = array();
    private $account = array();



    public function getAccountId()
    {
        return $this->accountId;
    }

    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response
        ;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount($account)
    {
        $this->account = $account;
    }

    public function getFilename()
    {
        return sprintf(
            'output_%s.XML',
            $this->getAccountId()
        );
    }

    public function getNitfDOM()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/templates');
        $twig = new \Twig_Environment($loader);
        $template = $twig->loadTemplate('nitf.twig');
        $nitfString = $template->render(array('user' => $this));
        $implementation = new \DOMImplementation();
        $dtd = $implementation->createDocumentType(
            'http://api.siteconfidence.co.uk/current/assets/dtds/api.dtd'
        );
        $nitfDOM = $implementation->createDocument('', '', $dtd);
        $nitfDOM->formatOutput = true;
        $nitfDOM->preserveWhiteSpace = false;
        $nitfDOM->loadXML($nitfString);
        $nitfDOM->encoding = 'ISO-8859-1';
        return $nitfDOM;
    }
}
