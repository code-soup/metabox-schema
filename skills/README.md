# Metabox Schema - Agent Skills

Agent skills for AI-assisted development, compatible with Skillshare.

## Available Skills

- **schema-definition** - Define field schemas with validation rules
- **field-renderer** - Render forms from schemas
- **template-creator** - Create custom field templates
- **validator** - Validate and sanitize user input
- **custom-field-registration** - Register custom field types
- **utilities** - Use utility classes

## Installation

See [SKILLSHARE.md](SKILLSHARE.md) for detailed installation instructions.

Quick start:
```bash
skillshare install github.com/code-soup/metabox-schema/skills --track
skillshare sync
```

## Structure

Each skill follows the Agent Skills specification:

```
skills/
├── schema-definition/
│   ├── SKILL.md
│   └── examples/
│       ├── basic-schema.md
│       ├── validation-example.md
│       └── value-resolution.md
├── field-renderer/
│   ├── SKILL.md
│   └── examples/
│       ├── basic-render.md
│       └── custom-template-render.md
└── ...
```

## Accuracy

Skills are maintained against the PHP package in `includes/`. After releases, see `docs/skills-audit-report.md` for the latest fact-check notes.

## Requirements

- Skillshare CLI installed
- AI tool with Skillshare support (Cursor, Windsurf, etc.)
- Package PHP 8.1+, WordPress 6.0+ (see root `readme.txt`)

## License

MIT
