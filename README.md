# SmartRedirector (for PHP)

SmartRedirector is a powerful and flexible redirection handler for PHP applications. It allows you to manage HTTP redirects based on time limits, IP rules, user-agent conditions, and more. Ideal for shortlinks, campaign tracking, or dynamic routing.

## 🔧 Features

- Easy and dynamic redirect rules
- HTTP status code control (301, 302, etc.)
- Expiry date/time support
- IP-based allow/block rules
- User-Agent based redirection
- JSON-based configuration for easy customization
- Composer-ready structure

## 📦 Installation

```
composer require zangane/SmartRedirector
```

## 🧠 Usage

```php
require "vendor/autoload.php";

use Zangane\SmartRedirector\Redirector;

$redirector = new Redirector("redirect-rules.json");
$redirector->handle();
```

## ⚙️ Configuration (redirect-rules.json)

```json
[
  {
    "path": "promo",
    "target": "https://example.com/promo",
    "status": 302,
    "expire_at": "2025-12-31 23:59:59",
    "ip_allow": ["1.2.3.4"],
    "user_agent_block": ["bot", "curl"]
  }
]
```

## 📁 Directory Structure

```
- src/
  - Redirector.php
- redirect-rules.json
- index.php
- logs/
  - access.log
- LICENSE
- README.md
```

## 📜 License

MIT License — see the [LICENSE](LICENSE) file for details.

## 🧬 Clone This Project

```
git clone https://github.com/zangane/SmartRedirector.git
```
