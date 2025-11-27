# Changelog

All notable changes to `forgepulse` will be documented in this file.

## [Unreleased]

## [1.2.0] - 2025-11-27

### Added

- **Workflow Versioning System**: Automatic version tracking for workflows with rollback capability
  - New `workflow_versions` table to store workflow snapshots
  - `WorkflowVersion` model with `restore()` and `compare()` methods
  - Version history modal with visual diff and comparison
  - Automatic version creation on workflow save (configurable)
  - Version retention and cleanup policies
- **Advanced Conditional Operators**: Extended conditional evaluation with 17 new operators
  - `starts_with`, `ends_with` for string prefix/suffix matching
  - `regex` and `not_regex` for pattern matching
  - `between` and `not_between` for range checks
  - `in_array` and `not_in_array` for array membership
  - `contains_all` and `contains_any` for array subset checks
  - `length_eq`, `length_gt`, `length_lt` for length comparisons
  - `is_null`, `is_not_null`, `is_empty`, `is_not_empty` for null/empty checks
- **Modern UI Redesign**:
  - Glassmorphism floating toolbar with blur effects
  - Draggable minimap and keyboard shortcuts panel
  - Interactive minimap with click-to-navigate functionality
  - Comprehensive keyboard shortcuts (⌘S, ⌘Z, ⌘⇧Z, Del, Esc, +/-)
  - Tooltips on all toolbar buttons and operators
  - Responsive design for mobile and tablet devices
- **Undo/Redo Functionality**: Full state management with 50-step history
- **Enhanced Dark Mode**: Global dark mode support across all components
- **Multi-Language Support**: Added Spanish (es), German (de), and Arabic (ar) translations
- **Operator Tooltips**: Helpful descriptions for all 22 conditional operators
- **Link Indicators**: Visual feedback for connected workflow steps

### Changed

- `Workflow` model now includes `versions()` relationship and versioning methods
- `WorkflowBuilder` component automatically creates versions on save when enabled
- `ConditionalEvaluator` service extended with 17 advanced comparison operators
- Toolbar redesigned with modern glassmorphism aesthetic
- Step editor now opens on double-click to prevent accidental opens during drag
- Dark mode CSS variables now apply globally to all components
- Improved canvas and grid styling for better visual hierarchy

### Fixed

- Fixed Livewire multiple root elements error in version history component
- Fixed theme toggle to apply globally across all UI components
- Fixed modal opening during step dragging
- Fixed grid toggle button visibility and active state
- Fixed version history modal close event handling
- Improved query performance with existing database indexes

## [1.1.0] - 2025-11-26

### Added

- **Timeout Orchestration**: Steps can now have a configured timeout. If a step exceeds the timeout, it will be terminated (requires `pcntl` extension).
- **Pause/Resume Workflows**: Workflows can be paused and resumed mid-execution with optional reason tracking.
- **Parallel Execution Paths**: Steps can be configured to execute in parallel using `execution_mode` and `parallel_group`.
- **Execution Scheduling**: Workflows can be scheduled for future execution with `scheduled_at` timestamp.
- **REST API**: New API endpoints for workflow monitoring and management:
  - `GET /api/forgepulse/workflows` - List all workflows
  - `GET /api/forgepulse/workflows/{id}` - Get workflow details
  - `GET /api/forgepulse/executions` - List all executions
  - `GET /api/forgepulse/executions/{id}` - Get execution details
  - `POST /api/forgepulse/executions/{id}/pause` - Pause execution
  - `POST /api/forgepulse/executions/{id}/resume` - Resume execution
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
