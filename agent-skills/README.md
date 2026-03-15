# Metabox Schema - Agent Skills

Agent skills for the Metabox Schema package, compatible with [Skillshare](https://skillshare.runkids.cc/).

## Available Skills

### metabox-schema/schema-definition
Define field schemas with validation and sanitization rules.

### metabox-schema/field-renderer
Render forms from schema definitions.

### metabox-schema/template-creator
Create custom field templates.

### metabox-schema/validator
Validate and sanitize user input against schemas.

### metabox-schema/utilities
Use utility classes (Constants, String_Formatter, Value_Resolver).

## Installation

### Local Installation

```bash
# From the metabox-schema project directory
skillshare install ./agent-skills --track
```

### From GitHub

```bash
skillshare install github.com/codesoup/metabox-schema/agent-skills
```

## Usage

After installation, sync to your AI tools:

```bash
skillshare sync
```

Check status:

```bash
skillshare status
skillshare list
```

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

