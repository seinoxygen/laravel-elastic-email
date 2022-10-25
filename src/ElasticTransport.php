<?php

namespace SeinOxygen\ElasticEmail;

use GuzzleHttp\ClientInterface;
use Illuminate\Mail\Transport\Transport;
use Swift_Mime_SimpleMessage;

class ElasticTransport extends Transport
{

    /**
     * Guzzle client instance.
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * The Elastic Email API key.
     * @var string
     */
    protected $key;

    /**
     * The Elastic Email username.
     * @var string
     */
    protected $account;


    /**
     * If the email is transactional.
     * @var string
     */
    protected $transactional;

    /**
     * THe Elastic Email API end-point.
     * @var string
     */
    protected $url = 'https://api.elasticemail.com/v2/email/send';

    /**
     * Create a new Elastic Email transport instance.
     *
     * @param \GuzzleHttp\ClientInterface $client
     * @param string $key
     * @param string $username
     *
     * @return void
     */
    public function __construct(ClientInterface $client, $config)
    {
        $this->client = $client;
        $this->key = $config['key'];
        $this->account = $config['account'];
        $this->transactional = $config['transactional'];
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = NULL)
    {
        $this->beforeSendPerformed($message);

        $isTransactional = $this->transactional;
        if(!is_null($message->getHeaders()->get('X-Transactional'))){
            $isTransactional = $message->getHeaders()->get('X-Transactional')->getFieldBody();
        }

        $data = [
            'api_key' => $this->key,
            'account' => $this->account,
            'msgTo' => $this->getEmailAddresses($message),
            'msgCC' => $this->getEmailAddresses($message, 'getCc'),
            'msgBcc' => $this->getEmailAddresses($message, 'getBcc'),
            'msgFrom' => $this->getFromAddress($message)['email'],
            'msgFromName' => $this->getFromAddress($message)['name'],
            'from' => $this->getFromAddress($message)['email'],
            'fromName' => $this->getFromAddress($message)['name'],
            'replyTo' => $this->getReplyToAddress($message)['email'],
            'replyToName' => $this->getReplyToAddress($message)['name'],
            'to' => $this->getEmailAddresses($message),
            'subject' => $message->getSubject(),
            'body_html' => $message->getBody(),
            'body_text' => $this->getText($message),
            'isTransactional' => $isTransactional
        ];

        $attachments = $message->getChildren();
        
        $response = $this->sendEmail($data, $attachments);

        // Inject the response data into the message headers.
        if($response) {
            $message->getHeaders()->addTextHeader('X-Message-ID', $response->messageid);
            $message->getHeaders()->addTextHeader('X-Transaction-ID', $response->transactionid);
        }

        $this->sendPerformed($message);

        return $response;
    }

    protected function sendEmail(array $data, array $attachments = []){
        if (!empty($attachments)) {
            $options['multipart'] = $this->parseMultipart($attachments, $data);
        } else {
            $options['form_params'] = $data;
        }

        $url = $this->url;
        $host = request()->getHost();
        $is_https = request()->server('HTTPS');

        if (str_contains($host, 'localhost') || $is_https != 'on') {
            $url = str_replace('https:', 'http:', $url);
        }

        try {
            $result = $this->client->request('POST', $url, $options);
        } catch (\Exception $e) {
            throw $e;
        }

        if($result->getStatusCode() == 200) {
            $response = $result->getBody()->getContents();
            $response = json_decode($response);
            
            if($response->success) {
                return $response->data;
            }
            else{
                throw new \Exception($response->error);
            }
        }

        return false;
    }

    /**
     * Get the plain text part.
     *
     * @param \Swift_Mime_SimpleMessage $message
     *
     * @return text|null
     */
    protected function getText(Swift_Mime_SimpleMessage $message)
    {
        $text = NULL;

        foreach ($message->getChildren() as $child) {
            if ($child->getContentType() == 'text/plain') {
                $text = $child->getBody();
            }
        }

        return $text;
    }

    /**
     * @param \Swift_Mime_SimpleMessage $message
     *
     * @return array
     */
    protected function getFromAddress(Swift_Mime_SimpleMessage $message)
    {
        return [
            'email' => array_keys($message->getFrom())[0],
            'name' => array_values($message->getFrom())[0],
        ];
    }

    /**
     * @param \Swift_Mime_SimpleMessage $message
     * @param string $method
     *
     * @return string
     */
    protected function getEmailAddresses(Swift_Mime_SimpleMessage $message, $method = 'getTo')
    {
        $data = call_user_func([$message, $method]);

        if (is_array($data)) {
            return implode(',', array_keys($data));
        }

        return '';
    }

    /**
     * @param \Swift_Mime_Message $message
     *
     * @return array
     */
    protected function getReplyToAddress(Swift_Mime_SimpleMessage $message)
    {
        if (!$message->getReplyTo()) {
            return $this->getFromAddress($message);
        }

        return [
            'email' => array_keys($message->getReplyTo())[0],
            'name' => array_values($message->getReplyTo())[0],
        ];
    }

    /**
     * Parse the attachments and add them to the multipart array.
     *
     * @param array $attachments
     * @param array $data
     *
     * @return array
     */
    private function parseMultipart(array $attachments, array $params): array
    {
        $result = [];

        foreach ($attachments as $key => $attachment) {
            if ($attachment instanceof \Swift_Attachment) {
                $result[] = [
                    'name' => 'file_' . $key,
                    'contents' => $attachment->getBody(),
                    'filename' => $attachment->getFilename()
                ];
            }
        }

        foreach ($params as $key => $param) {
            $result[] = [
                'name' => $key,
                'contents' => $param
            ];
        }

        return $result;
    }
}
