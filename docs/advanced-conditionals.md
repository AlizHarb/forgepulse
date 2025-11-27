# Advanced Conditional Operators (v1.2.0)

ForgePulse v1.2.0 introduces 10 powerful new conditional operators for more sophisticated workflow logic.

## Overview

In addition to the 15 existing operators, v1.2.0 adds:

- **Pattern Matching**: `regex`, `not_regex`
- **Range Checks**: `between`, `not_between`
- **Array Membership**: `in_array`, `not_in_array`
- **Array Subsets**: `contains_all`, `contains_any`
- **Length Comparisons**: `length_eq`, `length_gt`, `length_lt`

**Total Operators**: 25+

## Pattern Matching

### `regex`

Match values against regular expression patterns:

```php
$step->update([
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            [
                'field' => 'user.email',
                'operator' => 'regex',
                'value' => '/^[a-z0-9._%+-]+@company\.com$/i',
            ],
        ],
    ],
]);
```

**Use Cases**:

- Email domain validation
- Phone number format checking
- Custom ID format validation
- Complex string pattern matching

### `not_regex`

Inverse of regex - match values that DON'T match the pattern:

```php
[
    'field' => 'user.email',
    'operator' => 'not_regex',
    'value' => '/^test.*@/',  // Exclude test emails
]
```

## Range Checks

### `between`

Check if a value falls within a range (inclusive):

```php
[
    'field' => 'user.age',
    'operator' => 'between',
    'value' => [18, 65],  // Age between 18 and 65
]
```

**Use Cases**:

- Age validation
- Price ranges
- Date ranges
- Score thresholds

### `not_between`

Check if a value is outside a range:

```php
[
    'field' => 'order.total',
    'operator' => 'not_between',
    'value' => [0, 10],  // Orders over $10
]
```

## Array Membership

### `in_array`

Check if a value exists in an array field:

```php
[
    'field' => 'user.roles',  // Array field
    'operator' => 'in_array',
    'value' => 'admin',  // Check if 'admin' is in roles array
]
```

**Use Cases**:

- Role checking
- Tag validation
- Permission verification
- Category membership

### `not_in_array`

Check if a value does NOT exist in an array:

```php
[
    'field' => 'user.roles',
    'operator' => 'not_in_array',
    'value' => 'banned',  // User is not banned
]
```

## Array Subset Operations

### `contains_all`

Check if an array contains ALL specified values:

```php
[
    'field' => 'user.permissions',
    'operator' => 'contains_all',
    'value' => ['read', 'write', 'delete'],  // Must have all three
]
```

**Use Cases**:

- Required permissions check
- Mandatory tags validation
- Complete feature set verification

### `contains_any`

Check if an array contains ANY of the specified values:

```php
[
    'field' => 'user.features',
    'operator' => 'contains_any',
    'value' => ['premium_support', 'priority_queue', 'advanced_analytics'],
]
```

**Use Cases**:

- Premium feature detection
- Category matching
- Tag intersection

## Length Comparisons

### `length_eq`

Check if string/array length equals a value:

```php
[
    'field' => 'user.verification_code',
    'operator' => 'length_eq',
    'value' => 6,  // Code must be exactly 6 characters
]
```

### `length_gt`

Check if length is greater than a value:

```php
[
    'field' => 'product.description',
    'operator' => 'length_gt',
    'value' => 100,  // Description must be > 100 characters
]
```

### `length_lt`

Check if length is less than a value:

```php
[
    'field' => 'product.title',
    'operator' => 'length_lt',
    'value' => 50,  // Title must be < 50 characters
]
```

**Works with**:

- Strings (character count)
- Arrays (element count)

## Complex Examples

### Email Validation with Multiple Conditions

```php
$step->update([
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            // Valid email format
            [
                'field' => 'user.email',
                'operator' => 'regex',
                'value' => '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i',
            ],
            // Not a test email
            [
                'field' => 'user.email',
                'operator' => 'not_regex',
                'value' => '/^test.*@/',
            ],
            // From approved domains
            [
                'field' => 'user.email',
                'operator' => 'regex',
                'value' => '/@(company|partner)\.com$/',
            ],
        ],
    ],
]);
```

### Premium User Validation

```php
$step->update([
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            // Subscription in valid range
            [
                'field' => 'user.subscription_amount',
                'operator' => 'between',
                'value' => [50, 500],
            ],
            // Has required permissions
            [
                'field' => 'user.permissions',
                'operator' => 'contains_all',
                'value' => ['api_access', 'export_data'],
            ],
            // Either VIP or has referrals
            [
                'operator' => 'or',
                'rules' => [
                    [
                        'field' => 'user.tags',
                        'operator' => 'in_array',
                        'value' => 'vip',
                    ],
                    [
                        'field' => 'user.referrals',
                        'operator' => 'length_gt',
                        'value' => 5,
                    ],
                ],
            ],
        ],
    ],
]);
```

### Content Validation

```php
$step->update([
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            // Title length between 10 and 100
            [
                'field' => 'post.title',
                'operator' => 'length_gt',
                'value' => 10,
            ],
            [
                'field' => 'post.title',
                'operator' => 'length_lt',
                'value' => 100,
            ],
            // Has required tags
            [
                'field' => 'post.tags',
                'operator' => 'contains_any',
                'value' => ['tutorial', 'guide', 'documentation'],
            ],
            // Not in excluded categories
            [
                'field' => 'post.categories',
                'operator' => 'not_in_array',
                'value' => 'spam',
            ],
        ],
    ],
]);
```

## All Available Operators

### Basic Operators

| Operator | Description      | Example                                                           |
| -------- | ---------------- | ----------------------------------------------------------------- |
| `==`     | Equal            | `['field' => 'status', 'operator' => '==', 'value' => 'active']`  |
| `===`    | Strict equal     | `['field' => 'count', 'operator' => '===', 'value' => 5]`         |
| `!=`     | Not equal        | `['field' => 'status', 'operator' => '!=', 'value' => 'deleted']` |
| `!==`    | Strict not equal | `['field' => 'count', 'operator' => '!==', 'value' => '5']`       |
| `>`      | Greater than     | `['field' => 'age', 'operator' => '>', 'value' => 18]`            |
| `>=`     | Greater or equal | `['field' => 'score', 'operator' => '>=', 'value' => 80]`         |
| `<`      | Less than        | `['field' => 'price', 'operator' => '<', 'value' => 100]`         |
| `<=`     | Less or equal    | `['field' => 'quantity', 'operator' => '<=', 'value' => 10]`      |

### String Operators

| Operator      | Description        | Example                                                               |
| ------------- | ------------------ | --------------------------------------------------------------------- |
| `contains`    | String contains    | `['field' => 'email', 'operator' => 'contains', 'value' => '@gmail']` |
| `starts_with` | String starts with | `['field' => 'code', 'operator' => 'starts_with', 'value' => 'PRE-']` |
| `ends_with`   | String ends with   | `['field' => 'file', 'operator' => 'ends_with', 'value' => '.pdf']`   |

### Array Operators

| Operator | Description        | Example                                                                       |
| -------- | ------------------ | ----------------------------------------------------------------------------- |
| `in`     | Value in array     | `['field' => 'status', 'operator' => 'in', 'value' => ['active', 'pending']]` |
| `not_in` | Value not in array | `['field' => 'type', 'operator' => 'not_in', 'value' => ['spam', 'deleted']]` |

### Null/Empty Operators

| Operator       | Description        | Example                                                   |
| -------------- | ------------------ | --------------------------------------------------------- |
| `is_null`      | Value is null      | `['field' => 'deleted_at', 'operator' => 'is_null']`      |
| `is_not_null`  | Value is not null  | `['field' => 'verified_at', 'operator' => 'is_not_null']` |
| `is_empty`     | Value is empty     | `['field' => 'description', 'operator' => 'is_empty']`    |
| `is_not_empty` | Value is not empty | `['field' => 'tags', 'operator' => 'is_not_empty']`       |

### Advanced Operators (v1.2.0)

| Operator       | Description               | Example                                                                                |
| -------------- | ------------------------- | -------------------------------------------------------------------------------------- |
| `regex`        | Regex match               | `['field' => 'email', 'operator' => 'regex', 'value' => '/^[a-z]+@/']`                 |
| `not_regex`    | Regex not match           | `['field' => 'email', 'operator' => 'not_regex', 'value' => '/^test/']`                |
| `between`      | Value in range            | `['field' => 'age', 'operator' => 'between', 'value' => [18, 65]]`                     |
| `not_between`  | Value outside range       | `['field' => 'score', 'operator' => 'not_between', 'value' => [0, 50]]`                |
| `in_array`     | Value in array field      | `['field' => 'roles', 'operator' => 'in_array', 'value' => 'admin']`                   |
| `not_in_array` | Value not in array field  | `['field' => 'roles', 'operator' => 'not_in_array', 'value' => 'guest']`               |
| `contains_all` | Array contains all values | `['field' => 'perms', 'operator' => 'contains_all', 'value' => ['read', 'write']]`     |
| `contains_any` | Array contains any value  | `['field' => 'tags', 'operator' => 'contains_any', 'value' => ['urgent', 'critical']]` |
| `length_eq`    | Length equals             | `['field' => 'code', 'operator' => 'length_eq', 'value' => 6]`                         |
| `length_gt`    | Length greater than       | `['field' => 'desc', 'operator' => 'length_gt', 'value' => 100]`                       |
| `length_lt`    | Length less than          | `['field' => 'title', 'operator' => 'length_lt', 'value' => 50]`                       |

## Examples

See [advanced-conditionals-example.php](../examples/advanced-conditionals-example.php) for comprehensive examples of all operators.

## Best Practices

### 1. Use Appropriate Operators

```php
// Good - specific operator
['field' => 'email', 'operator' => 'regex', 'value' => '/^[a-z]+@company\.com$/']

// Less efficient - multiple conditions
['field' => 'email', 'operator' => 'contains', 'value' => '@company.com']
```

### 2. Combine Operators Logically

```php
[
    'operator' => 'and',
    'rules' => [
        ['field' => 'age', 'operator' => 'between', 'value' => [18, 65]],
        ['field' => 'permissions', 'operator' => 'contains_all', 'value' => ['read', 'write']],
    ],
]
```

### 3. Test Regex Patterns

```php
// Test your regex before using
$pattern = '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i';
$test = 'user@example.com';

if (preg_match($pattern, $test)) {
    // Pattern works, use in workflow
}
```

---

**Next**: [Workflows](workflows.md)  
**Previous**: [Versioning](versioning.md)
