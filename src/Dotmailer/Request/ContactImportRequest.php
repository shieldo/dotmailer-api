<?php

namespace Dotmailer\Request;

use Dotmailer\Config;
use Dotmailer\Entity\ContactImport;
use Dotmailer\Collection\ContactCollection;
use Dotmailer\Request\AbstractContactImportRequest;


class ContactImportRequest extends AbstractContactImportRequest
{

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->request = new Request($config);
        $this->request->setEndpoint('contacts');
    }

    /**
     * Import a list of new contacts.
     * http://api.dotmailer.com/v2/help/wadl#AddressBookContactsImport
     *
     * @param  ContactCollection $contacts    A collection of contacts to import.
     * @return ContactImport                  A ContactImport record, including the import ID.
     */
    public function create(ContactCollection $contacts)
    {
        $csv = $this->createCSVFromCollection($contacts);
        $args = array(
            'filename' => time().'.csv',
            'data' => base64_encode($csv)
        );
        $response = $this->request->send('post', '/import', $args);
        return new ContactImport($response);
    }


}
