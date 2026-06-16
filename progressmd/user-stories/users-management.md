# Users Management User Story

## Story Reference

- Story ID: `US-USER-001`
- Module: Users Management
- Status: Implemented

## Story

As an authorized backend user,
I want to list, create, view, update, and delete users,
So that I can manage user accounts through the API.

## Planned Endpoints

- `GET /api/users`
- `GET /api/users?search=keyword`
- `POST /api/users`
- `GET /api/users/{id}`
- `PUT /api/users/{id}`
- `PATCH /api/users/{id}`
- `DELETE /api/users/{id}`

## Notes

- The users module is implemented under `Api/Users`
- List responses support pagination through `per_page`
- List responses support optional search through `search` on `name` and `email`
- `admin` can create, update, and delete users
- `manager` can read user listings and user details
- `customer` cannot access the users management module
- Authorization is enforced in the backend with `UserPolicy`
