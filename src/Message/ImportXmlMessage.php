<?php

namespace App\Message;

class ImportXmlMessage{

    public function __construct(private string $xml)
    {
    }

    public function getXml():string{
        return $this->xml;
    }

}