# Changelog

All notable changes to `flowforge` will be documented in this file.

## [1.1.0] - 2025-11-26

### Added

- **Timeout Orchestration**: Steps can now have a configured timeout. If a step exceeds the timeout, it will be terminated (requires `pcntl` extension).
- **Pause/Resume Workflows**: Workflows can be paused and resumed mid-execution with optional reason tracking.
- **Parallel Execution Paths**: Steps can be configured to execute in parallel using `execution_mode` and `parallel_group`.
- **Execution Scheduling**: Workflows can be scheduled for future execution with `scheduled_at` timestamp.
- **REST API**: New API endpoints for workflow monitoring and management:
  - `GET /api/flowforge/workflows` - List all workflows
  - `GET /api/flowforge/workflows/{id}` - Get workflow details
  - `GET /api/flowforge/executions` - List all executions
  - `GET /api/flowforge/executions/{id}` - Get execution details
  - `POST /api/flowforge/executions/{id}/pause` - Pause execution
  - `POST /api/flowforge/executions/{id}/resume` - Resume execution
- **Dark Mode Support**: Full dark mode support for all UI components (enabled by default).
- **API Resources**: New JSON resources for workflows, executions, steps, and logs.
- **Documentation Improvements**:
  - Sticky sidebar menu for better navigation
  - "Back to Top" button for long pages
  - Fixed header links across all documentation pages
  - Created comprehensive UPGRADE.md guide

### Changed

- Updated license year to 2025
- Improved documentation navigation and UX
- Enhanced workflow engine to check for pause state during execution

### Fixed

- Fixed GitHub Actions badge URL in README
- Fixed documentation header links to properly navigate between pages

## [1.0.0] - 2025-11-25

### Added

- Initial release
- Drag-and-drop workflow builder with Livewire 4 and Alpine.js
- Conditional branching with complex boolean expressions
- Workflow templates (save, load, reuse)
- Real-time execution tracking
- Multiple step types (action, condition, delay, notification, webhook, event, job)
- Role-based access control
- Laravel 12 event integration
- Queued job execution with uniqueness constraint
- Comprehensive test suite with Pest 3
- Dark mode support
- Execution logging with performance metrics
- Template import/export functionality
- Visual workflow canvas with zoom and grid snapping
- **Multi-language support** (English, Spanish, French, German, Arabic)
- PHP 8.3+ enums with helper methods
- Readonly classes and properties
- Laravel scope attributes
- Performance optimizations with database indexes
