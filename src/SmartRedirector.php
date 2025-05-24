<?php

namespace SmartRedirector;

class SmartRedirector
{
    protected $url;
    protected $statusCode = 302;
    protected $expireAt;
    protected $allowedIPs = [];
    protected $blockedIPs = [];
    protected $allowedUserAgents = [];
    protected $logPath;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function statusCode(int $code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function expireAt($datetime)
    {
        $this->expireAt = strtotime($datetime);
        return $this;
    }

    public function allowIP($ip)
    {
        $this->allowedIPs[] = $ip;
        return $this;
    }

    public function blockIP($ip)
    {
        $this->blockedIPs[] = $ip;
        return $this;
    }

    public function allowUserAgent($agent)
    {
        $this->allowedUserAgents[] = $agent;
        return $this;
    }

    public function setLogFile($path)
    {
        $this->logPath = $path;
        return $this;
    }

    public function execute()
    {
        $clientIP = $_SERVER["REMOTE_ADDR"] ?? "";
        $userAgent = $_SERVER["HTTP_USER_AGENT"] ?? "";

        // Check expiration
        if ($this->expireAt && time() > $this->expireAt) {
            $this->log("Redirect blocked: expired");
            return;
        }

        // Check blocked IPs
        if (in_array($clientIP, $this->blockedIPs)) {
            $this->log("Redirect blocked: IP $clientIP is blocked");
            return;
        }

        // Check allowed IPs
        if (!empty($this->allowedIPs) && !in_array($clientIP, $this->allowedIPs)) {
            $this->log("Redirect blocked: IP $clientIP is not allowed");
            return;
        }

        // Check user agent
        if (!empty($this->allowedUserAgents)) {
            $matched = false;
            foreach ($this->allowedUserAgents as $ua) {
                if (stripos($userAgent, $ua) !== false) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $this->log("Redirect blocked: UA $userAgent not allowed");
                return;
            }
        }

        // Do redirect
        $this->log("Redirected to {$this->url} with status {$this->statusCode}");
        header("Location: {$this->url}", true, $this->statusCode);
        exit;
    }

    protected function log($message)
    {
        if ($this->logPath) {
            $entry = "[" . date("Y-m-d H:i:s") . "] " . $message . "\n";
            file_put_contents($this->logPath, $entry, FILE_APPEND);
        }
    }
}
