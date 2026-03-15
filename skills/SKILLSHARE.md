# Installing Metabox Schema Skills via Skillshare

Agent skills for the Metabox Schema package, compatible with [Skillshare](https://skillshare.runkids.cc/).

## Prerequisites

Install Skillshare CLI:

```bash
brew install runkids/tap/skillshare
```

Or see [installation guide](https://skillshare.runkids.cc/docs/learn/getting-started).

## Installation

### From GitHub

```bash
# Install all skills
skillshare install github.com/code-soup/metabox-schema/skills --track

# Sync to your AI tools
skillshare sync
```

### From Local Clone

```bash
# Clone the repository
git clone https://github.com/code-soup/metabox-schema.git
cd metabox-schema

# Install skills
skillshare install ./skills --track
skillshare sync
```

### Install Specific Skills

```bash
# Install only specific skills
skillshare install github.com/code-soup/metabox-schema/skills \
  -s schema-definition,validator,field-renderer

skillshare sync
```

## Available Skills

- **schema-definition** - Define field schemas with validation rules
- **field-renderer** - Render forms from schemas
- **template-creator** - Create custom field templates
- **validator** - Validate and sanitize user input
- **custom-field-registration** - Register custom field types
- **utilities** - Use utility classes

## Verify Installation

```bash
# Check installed skills
skillshare list

# Check sync status
skillshare status
```

## Update Skills

```bash
# Update all tracked skills
skillshare update --all
skillshare sync
```

## Uninstall

```bash
# Uninstall specific skills
skillshare uninstall schema-definition
skillshare uninstall field-renderer
skillshare uninstall template-creator
skillshare uninstall validator
skillshare uninstall custom-field-registration
skillshare uninstall utilities

skillshare sync
```

## Documentation

- [Main README](../README.md) - Package documentation
- [Skills README](README.md) - Skills overview
- [Skillshare Docs](https://skillshare.runkids.cc/) - Skillshare documentation

## License

MIT

