<?php

namespace Zangane\SmartRedirector;

class Redirector
{
    private $rules = [];

    public function __construct($rulesFile)
    {
        if (!file_exists($rulesFile)) {
            throw new \Exception("Redirect rules file not found.");
        }

        $json = file_get_contents($rulesFile);
        $this->rules = json_decode($json, true);

        if (!is_array($this->rules)) {
            throw new \Exception("Invalid JSON in redirect rules file.");
        }
    }

    public function handle()
    {
        $requestUri = trim($_SERVER["REQUEST_URI"], "/");
        $ip = $_SERVER["REMOTE_ADDR"] ?? "";
        $userAgent = $_SERVER["HTTP_USER_AGENT"] ?? "";

        foreach ($this->rules as $rule) {
            if ($rule["path"] !== $requestUri) {
                continue;
            }

            // Expiration check
            if (isset($rule["expire_at"]) && strtotime($rule["expire_at"]) < time()) {
                continue;
            }

            // IP check
            if (isset($rule["ip_allow"]) && !in_array($ip, $rule["ip_allow"])) {
                continue;
            }

            // User agent block
            if (isset($rule["user_agent_block"])) {
                foreach ($rule["user_agent_block"] as $blocked) {
                    if (stripos($userAgent, $blocked) !== false) {
                        continue 2;
                    }
                }
            }

            // Logging
            $this->logRedirect($rule["path"], $rule["target"], $ip, $userAgent);

            // Perform redirect
            $status = $rule["status"] ?? 302;
            header("Location: " . $rule["target"], true, $status);
            exit;
        }

        // Optional: You can add a fallback 404 or message her
