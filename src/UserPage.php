<?php

namespace Balraj\ProcessXML;

/**
 * Get content on user level.
 */
class UserPage {

    private $xpath;
    public $page_file;
    private $doc;
    private $dir;

    public function __construct($page_file) {
        $this->page_file = $page_file;
        $this->doc = new \DOMDocument();
        if (file_exists($this->page_file)) {
            $this->doc->load($this->page_file);
            $this->xpath = new \DOMXpath($this->doc);
            $this->dir = dirname($this->page_file);
        }
    }

    /**
     * Check xml has SiteConfidence.
     * @return boolean
     */
    public function hasSiteConfidence() {
        $siteConfidence_node = $this->xpath->query("//SiteConfidence[@Version='current']");
        return $siteConfidence_node->length > 0;
    }

    /**
     * Get AccountId.
     * @return string
     */
    public function getAccountId()
    {
        $AccountId = $this->xpath->query(
            "//Request/@AccountId"
        )->item(0);
        if (null !== $AccountId) {
            return $AccountId->nodeValue;
        }
    }

    /**
     * Get Response.
     * @return array
     */
    public function getResponse()
    {
        $status = $this->xpath->query(
            "//Response/@Status"
        )->item(0);
        $code = $this->xpath->query(
            "//Response/@Code"
        )->item(0);
        $message = $this->xpath->query(
            "//Response/@Message"
        )->item(0);

        $response['status'] = null !== $status ? $status->nodeValue : '';
        $response['code'] = null !== $code ? $code->nodeValue : '';
        $response['message'] = null !== $message ? $message->nodeValue : '';
        return $response;
    }

    public function getAccount()
    {
        $account_id = $this->xpath->query(
            "//Response/Account/@AccountId"
        )->item(0);
        $account_name = $this->xpath->query(
            "//Response/Account/@Name"
        )->item(0);
        $pages = $this->xpath->query(
            "//Response/Account/Pages/@id"
        )->item(0);

        $response['accountId'] = null !== $account_id ? $account_id->nodeValue : '';
        $response['accountName'] = null !== $account_name ? $account_name->nodeValue : '';
        $response['pages'] = null !== $pages ? $pages->nodeValue : '';
        return $response;
    }
}
