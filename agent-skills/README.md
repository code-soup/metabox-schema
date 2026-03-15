# Metabox Schema - Agent Skills

Agent skills for AI-assisted development, compatible with [Skillshare](https://skillshare.runkids.cc/).

## Available Skills

- **schema-definition** - Define field schemas with validation rules
- **field-renderer** - Render forms from schemas
- **template-creator** - Create custom field templates
- **validator** - Validate and sanitize user input
- **custom-field-registration** - Register custom field types
- **utilities** - Use utility classes

See main [README.md](../README.md#agent-skills) for installation instructions.

## Structure

Each skill follows the [Agent Skills specification](https://agentskills.io/specification):

```
metabox-schema/
├── schema-definition/
│   ├── SKILL.md
│   └── assets/
│       ├── basic-schema.php
│       ├── validation-example.php
│       └── value-resolution.php
├── field-renderer/
│   ├── SKILL.md
│   └── assets/
│       ├── basic-render.php
│       └── custom-template-render.php
└── ...
```

## Requirements

- [Skillshare CLI](https://skillshare.runkids.cc/) installed
- AI tool with Skillshare support (Cursor, Windsurf, etc.)

## License

MIT
