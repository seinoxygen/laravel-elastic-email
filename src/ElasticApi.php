<?php

namespace SeinOxygen\ElasticEmail;

use SeinOxygen\ElasticEmail\Actions;
use SeinOxygen\ElasticEmail\Actions\ApiClient;

class ElasticApi extends ApiClient
{
    public function Account()
    {
        return new Actions\Account();
    }

    public function Attachment()
    {
        return new Actions\Attachment();
    }

    public function Campaign()
    {
        return new Actions\Campaign();
    }

    public function Channel()
    {
        return new Actions\Channel();
    }

    public function Contact()
    {
        return new Actions\Contact();
    }

    public function Domain()
    {
        return new Actions\Domain();
    }

    public function EmailList()
    {
        return new Actions\EEList();
    }

    public function Email()
    {
        return new Actions\Email();
    }

    public function Export()
    {
        return new Actions\Export();
    }

    public function Log()
    {
        return new Actions\Log();
    }

    public function Segment()
    {
        return new Actions\Segment();
    }

    public function SMS()
    {
        return new Actions\SMS();
    }

    public function Survay()
    {
        return new Actions\Survey();
    }

    public function Template()
    {
        return new Actions\Template();
    }
}

