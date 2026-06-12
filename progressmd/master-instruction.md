# Master Instruction

This file contains the standing rules and guard rails for all future development in this Laravel API project.

## Core Rules

- Always follow the architectural rules from `laravel-api-backend-docs`
- Prefer clean, maintainable Laravel code over shortcuts
- Keep controllers thin and business logic outside controllers
- Use the documented backend patterns consistently across features
- Keep naming, folder structure, and response patterns aligned with the architecture docs

## Mandatory Process For Every Development Task

1. Review the relevant architecture document before implementation.
2. Confirm whether the task introduces a new user flow or user story.
3. If it does, add that story to `user-stories.md`.
4. Implement the task using the agreed Laravel architecture and coding standards.
5. Mark completed and pending work in `progress-tracker.md`.

## Required Documentation Discipline

- `progress-tracker.md` must always reflect the latest completed and pending tasks
- `user-stories.md` must always include newly introduced stories during development
- `master.md` should remain the top-level entry point for this documentation set

## Architecture Source Of Truth

Use these documents as the project reference:

- `../laravel-api-backend-docs/docs/architecture/01-overview.md`
- `../laravel-api-backend-docs/docs/architecture/02-folder-structure.md`
- `../laravel-api-backend-docs/docs/architecture/03-request-lifecycle.md`
- `../laravel-api-backend-docs/docs/architecture/05-service-action-repository-pattern.md`
- `../laravel-api-backend-docs/docs/architecture/07-api-response-standard.md`
- `../laravel-api-backend-docs/docs/architecture/08-validation.md`
- `../laravel-api-backend-docs/docs/architecture/09-authentication.md`
- `../laravel-api-backend-docs/docs/architecture/10-authorization.md`
- `../laravel-api-backend-docs/docs/architecture/12-testing-strategy.md`
- `../laravel-api-backend-docs/docs/architecture/13-security-checklist.md`
- `../laravel-api-backend-docs/docs/architecture/14-coding-standards.md`

## Default Expectations

- Keep API design consistent
- Keep validation explicit
- Keep errors standardized
- Keep security considerations visible
- Keep testing aligned with the documented strategy
