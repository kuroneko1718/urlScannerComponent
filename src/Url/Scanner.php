<?php

namespace My\Scanner\Url;

class Scanner
{
    /**
     * @var array 一个由URL组成的数组
     */
    protected $urls;
    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * 构造方法
     *
     * @param array $urls 一个需要扫描的URL数组
     * 
     */
    public function __construct(array $urls)
    {
        $this->urls = $urls;
        $this->httpClient = new \GuzzleHttp\Client();
    }

    /**
     * 获取死链
     *
     * @return array
     * 
     */
    public function getInvalidUrls() : array
    {
        $invalidUrls = [];
        foreach ($this->urls as $url) {
            try {
                $statusCode = $this->getStatusCodeForUrl($url);
            } catch (\Exception $e) {
                $statusCode = 500;
            }

            if ($statusCode >= 400) {
                array_push($invalidUrls, [
                    'url' => $url,
                    'status' => $statusCode
                ]);
            }
        }
        return $invalidUrls;
    }

    /**
     * 获取指定URL的HTTP状态码
     *
     * @param mixed $url URL链接
     * 
     * @return int HTTP状态码
     * 
     */
    public function getStatusCodeForUrl($url) : int
    {
        $httpResponse = $this->httpClient->options($url);
        return $httpResponse->getStatusCode();
    }
}