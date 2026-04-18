# Complete PHP Features Reference

All 69 modern PHP features from haPHPiness.com

## Syntax & Language

1. **Named Arguments** (8.0) - Skip optional params
2. **Arrow Functions** (7.4) - Auto-capture scope
3. **Match Expression** (8.0) - Strict comparison, returns value
4. **Null Coalescing** (7.0) - `??` operator
5. **Null Coalescing Assignment** (7.4) - `??=` operator
6. **Spaceship Operator** (7.0) - `<=>` for sorting
7. **Array Destructuring** (7.1) - Extract values
8. **Array Unpacking** (8.1) - `...$array` with string keys
9. **First-Class Callables** (8.1) - `strlen(...)` syntax
10. **Numeric Literal Separators** (7.4) - `1_000_000`

## Type System

11. **Typed Properties** (7.4) - Class property types
12. **Union Types** (8.0) - `int|string`
13. **Intersection Types** (8.1) - `Countable&Iterator`
14. **Mixed Type** (8.0) - Explicit mixed
15. **Void Type** (7.1) - No return value
16. **Never Type** (8.1) - Never returns
17. **Static Return Type** (8.0) - Late static binding
18. **Constructor Property Promotion** (8.0) - DRY constructors
19. **Readonly Properties** (8.1) - Immutable properties
20. **Readonly Classes** (8.2) - Immutable classes
21. **Nullable Types** (7.1) - `?string`

## Object-Oriented

22. **Enums** (8.1) - Type-safe enumerations
23. **Backed Enums** (8.1) - Enums with scalar values
24. **Attributes** (8.0) - Metadata annotations
25. **Anonymous Classes** (7.0) - On-the-fly classes
26. **Final Class Constants** (8.1) - Prevent override

## String Functions

27. **str_contains()** (8.0) - Check substring
28. **str_starts_with()** (8.0) - Check prefix
29. **str_ends_with()** (8.0) - Check suffix

## Array Functions

30. **array_key_first()** (7.3) - Get first key
31. **array_key_last()** (7.3) - Get last key
32. **array_is_list()** (8.1) - Check if list

## Error Handling

33. **Throw Expressions** (8.0) - Throw in expressions
34. **Non-capturing Catches** (8.0) - Catch without variable
35. **Stringable Interface** (8.0) - Type for __toString

## Security

36. **password_hash()** (5.5) - Secure password hashing
37. **password_verify()** (5.5) - Verify hashed passwords
38. **random_int()** (7.0) - Cryptographically secure random
39. **random_bytes()** (7.0) - Secure random bytes

## Performance

40. **OPcache** - Bytecode caching
41. **JIT Compilation** (8.0) - Just-in-time compilation
42. **Preloading** (7.4) - Preload files on server start
43. **FFI** (7.4) - Foreign Function Interface

## PDO & Database

44. **Named Parameters** (PDO) - `:placeholder` syntax
45. **PDO::ATTR_EMULATE_PREPARES** - True prepared statements

## Streams & I/O

46. **php://memory** - In-memory stream
47. **php://temp** - Temp file stream
48. **stream_get_meta_data()** - Stream information

## JSON

49. **JSON_THROW_ON_ERROR** (7.3) - Exception on error
50. **json_validate()** (8.3) - Validate JSON without parsing

## Reflection

51. **ReflectionEnum** (8.1) - Reflect on enums
52. **ReflectionAttribute** (8.0) - Reflect on attributes

## SPL

53. **WeakMap** (8.0) - Weak references in maps
54. **Fiber** (8.1) - Cooperative multitasking

## Date & Time

55. **DateTime::createFromImmutable()** (7.3) - Convert DateTimeImmutable
56. **DateTimeInterface** - Common interface

## Operators

57. **Null-safe Operator** (8.0) - `?->` chaining
58. **Ternary Operator Short** (5.3) - `?:` elvis

## Constants

59. **Class Constant Visibility** (7.1) - private/protected constants
60. **::class** (5.5) - Fully qualified class name

## Variadic Functions

61. **Variadic Parameters** (5.6) - `...$args`
62. **Argument Unpacking** (5.6) - `...$array`

## Generator

63. **Generators** (5.5) - Memory-efficient iteration
64. **yield from** (7.0) - Generator delegation

## Misc

65. **Trailing Comma** (8.0) - In parameter lists
66. **Mixed Type** (8.0) - Explicit any type
67. **Deprecation Warnings** - Silent deprecations removed
68. **Composer** (2.0+) - Dependency management
69. **Built-in Dev Server** - `php -S localhost:8000`

## Version Matrix

| Version | Key Features |
|---------|--------------|
| 7.0 | Type declarations, null coalescing, spaceship |
| 7.1 | Nullable types, void, class const visibility |
| 7.3 | Trailing commas, JSON_THROW_ON_ERROR |
| 7.4 | Typed properties, arrow functions, preloading |
| 8.0 | Named args, union types, match, attributes, JIT |
| 8.1 | Enums, readonly, never, fibers, first-class callables |
| 8.2 | Readonly classes, DNF types |
| 8.3 | json_validate(), typed class constants |

Source: https://haphpiness.com (entries.json analyzed)
