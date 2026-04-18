---
name: php-best-practices
description: Modern PHP best practices from haPHPiness.com covering type safety, enums, named arguments, match expressions, security, and performance. Use when writing PHP code, choosing language features, or making architectural decisions for PHP 8.1+ projects.
metadata:
  author: haPHPiness.com
  version: "1.0"
  php-version: "8.1+"
  source: https://haphpiness.com
---

# PHP Best Practices

Modern PHP best practices based on 69 features from haPHPiness.com.

**Minimum PHP**: 8.1+  
**Recommended**: 8.3+

## When to Use

- Writing new PHP code
- Refactoring legacy code
- Choosing between language features
- Code review and quality checks
- Setting project PHP version requirements
- Making architectural decisions
- Teaching/learning modern PHP

## Core Language Features

### Named Arguments (8.0+)
Skip optional parameters, self-documenting code.

```php
// Bad
htmlspecialchars($string, ENT_COMPAT | ENT_HTML401, 'UTF-8', false);

// Good
htmlspecialchars($string, double_encode: false);
```

### Union Types (8.0+)
Express exact function contracts.

```php
function process(int|string $input): string|false {
    return is_int($input) ? (string)$input : $input;
}
```

### Match Expression (8.0+)
Strict comparison, returns value, no fall-through.

```php
$text = match($statusCode) {
    200, 201 => 'Success',
    404      => 'Not Found',
    default  => 'Unknown',
};
```

### Enums (8.1+)
Type-safe fixed sets, impossible to misuse.

```php
enum Status: string {
    case Active = 'active';
    case Pending = 'pending';
    case Archived = 'archived';
}

function updateStatus(Status $status): void {
    // Type-safe, autocomplete works
}
```

### Arrow Functions (7.4+)
Auto-capture scope, implicit return.

```php
$doubled = array_map(fn($n) => $n * 2, $numbers);
usort($users, fn($a, $b) => $a->name <=> $b->name);
```

### Modern String Functions (8.0+)
Clear intent, no footguns.

```php
// Bad
if (strpos($url, 'https') !== false) { }

// Good
if (str_contains($url, 'https')) { }
if (str_starts_with($file, '/var/www')) { }
if (str_ends_with($file, '.php')) { }
```

## Type System

### Typed Properties (7.4+)
Always declare types.

```php
class Product {
    public string $name;
    public float $price;
    public ?string $description = null;
}
```

### Constructor Property Promotion (8.0+)
DRY for value objects.

```php
class User {
    public function __construct(
        private string $name,
        private string $email,
        private int $age,
    ) {}
}
```

### Readonly (8.1+)
Engine-enforced immutability.

```php
readonly class Money {
    public function __construct(
        public int $amount,
        public string $currency,
    ) {}
}
```

### Never Type (8.1+)
Mark exit points explicitly.

```php
function abort(int $code, string $message): never {
    throw new HttpException($code, $message);
}
```

## Security

### PDO Prepared Statements
SQL injection prevention by design.

```php
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
```

### Password Hashing
Bcrypt by default, secure defaults.

```php
$hash = password_hash($password, PASSWORD_DEFAULT);
if (password_verify($input, $hash)) {
    // Authenticated
}
```

## Performance

### Enable OPcache in Production
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.validate_timestamps=0
opcache.jit_buffer_size=100M
opcache.jit=tracing
```

### Use Built-in Functions
Native code faster than userland.

```php
$result = array_map(fn($n) => $n * 2, $numbers);
$filtered = array_filter($items, fn($i) => $i->active);
```

## Code Quality

### Strict Types Always
```php
declare(strict_types=1);
```

### Static Analysis (PHPStan Level 9)
```bash
composer require --dev phpstan/phpstan
vendor/bin/phpstan analyse src --level=9
```

## What to Avoid

❌ `switch` → use `match`  
❌ `strpos() !== false` → use `str_contains()`  
❌ Class constants for enums → use `enum`  
❌ Docblock types → use native types  
❌ Manual `isset()` checks → use `??`  
❌ `extract()` or `eval()`  
❌ `empty()` → be explicit

## Version Requirements

| Feature | Min Version |
|---------|-------------|
| Named Arguments | 8.0 |
| Union Types | 8.0 |
| Match | 8.0 |
| Enums | 8.1 |
| Readonly | 8.1 |
| Never | 8.1 |

See [references/FEATURES.md](references/FEATURES.md) for complete feature list.
