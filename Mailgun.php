<?php

class Mailgun
{
    private $mailgunServer = 'https://api.mailgun.net/v3';
    private $domain = '';
    private $privateKey = '';
    private $publicKey = '';
    private $sender = '';
    private $service = '';

    private $defaults = [
        'o:tracking' => 'yes',
        'o:tracking-clicks' => 'yes',
        'o:tracking-opens' => 'yes',
        'o:tag' => '',
    ];

    public function __construct($config)
    {
        $this->domain = $config['domain'];
        $this->privateKey = $config['privateKey'];
        $this->publicKey = $config['publicKey'];
        $this->sender = $config['sender'];
        $this->service = $config['service'];
    }

    /**
     * send mail
     * @return string response messages from mailgun
     */
    public function send($to, $subject, $html, $option = [])
    {
        $from = $this->sender . '<' . $this->service . '>';
        $data = [
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'html' => $html,
            'h:Reply-To' => $from,
        ];

        $data = array_merge([], $data, $this->defaults, $option);

        $url = $this->mailgunServer . '/' . $this->domain . '/messages';
        $response = $this->request($url, $data, [], $this->privateKey);
        return $response;
    }

    /**
     * validate email
     * @return string validate result
     */
    public function validate($to)
    {
        $data = array(
            'address' => $to,
        );
        $url = $this->mailgunServer . '/address/validate?' . http_build_query($data);
        $response = $this->request($url, [], [], $this->publicKey);
        return $response;
    }

    /**
     * [request description]
     * @param  string $url       request url
     * @param  array  $post_data
     * @param  array  $head
     * @param  string $key
     * @return string
     */
    public function request($url, $post_data = [], $head = [], $key = "")
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);

        if (!empty($head)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
        }

        if (!empty($post_data)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
        }

        if (!empty($key)) {
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, 'api:' . $key);
        }

        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($curl);

        return $body;
    }

}
