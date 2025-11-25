# Contributing to FlowForge

Thank you for considering contributing to FlowForge! This document outlines the process for contributing to this project.

## Code of Conduct

Please be respectful and constructive in all interactions.

## How to Contribute

### Reporting Bugs

- Use the GitHub issue tracker
- Include a clear description of the problem
- Provide steps to reproduce
- Include relevant code samples
- Specify your environment (PHP version, Laravel version, etc.)

### Suggesting Features

- Use the GitHub issue tracker
- Clearly describe the feature and its benefits
- Provide use cases and examples
- Be open to discussion and feedback

### Pull Requests

1. Fork the repository
2. Create a new branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Write or update tests
5. Ensure all tests pass (`composer test`)
6. Run static analysis (`composer analyse`)
7. Format your code (`composer format`)
8. Commit your changes (`git commit -m 'Add amazing feature'`)
9. Push to the branch (`git push origin feature/amazing-feature`)
10. Open a Pull Request

## Development Setup

1. Clone the repository:

```bash
git clone https://github.com/alizharb/flowforge.git
cd flowforge
```

2. Install dependencies:

```bash
composer install
```

3. Run tests:

```bash
composer test
```

## Coding Standards

- Follow PSR-12 coding standards
- Use Laravel best practices
- Write descriptive commit messages
- Add PHPDoc blocks for all public methods
- Keep methods focused and concise
- Write tests for new features

## Testing

- All new features must include tests
- Maintain or improve code coverage
- Use Pest for writing tests
- Test both success and failure scenarios

## Documentation

- Update README.md for new features
- Add inline code comments for complex logic
- Update CHANGELOG.md following Keep a Changelog format
- Include usage examples

## Questions?

Feel free to open an issue or contact harbzali@gmail.com

Thank you for contributing to FlowForge!
