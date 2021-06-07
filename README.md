# Tasks API

A simple API done in Laravel 8 for managing users tasks.

It works with Docker containers and Event dispatchers.

For development purposes, it's using the PHP built-in server.

The authenticated resources requires a _Bearer Token_ which can be obtained from the login resource.

## Requirements:
- [Docker](https://docs.docker.com/engine/install/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Configuration:
1. Create `.env` file:
```shell
cp .env.example .env
```

2. Create and run Docker containers:
```shell
docker-compose up
```

## Resources:

### api/v1/users
- Description: List all active users.
- Method: `GET`
- Needs authentication: `false`
- Parameters:
- Example:
    - Input:
        ```
        ```
    - Output:
        - Status: `200`
        - Response:
        ```json
        [
            {
                "id": 1,
                "name": "John Doe",
                "email": "johndoe@test.com",
                "email_verified_at": null,
                "deleted_at": null,
                "created_at": "2021-06-07T11:43:14.000000Z",
                "updated_at": "2021-06-07T11:43:14.000000Z"
            },
            {
                "id": 2,
                "name": "Thiago Bittencourt",
                "email": "thiagobitt86@gmail.com",
                "email_verified_at": null,
                "deleted_at": null,
                "created_at": "2021-06-07T11:54:54.000000Z",
                "updated_at": "2021-06-07T11:54:54.000000Z"
            }
        ]
        ```

### api/v1/users/register
- Description: Register a user.
- Method: `POST`
- Needs authentication: `false`
- Parameters:
    - `name`
        - Description: User name.
        - Type: `string`
    - `email`
        - Description: User email.
        - Type: `string`
    - `password`
        - Description: User password.
        - Type: `string`
- Example:
    - Input:
        ```json
        {
            "name": "John Doe",
            "email": "johndoe@test.com",
            "password": "123456"
        }
        ```
    - Output:
        - Status: `200`
        - Response:
        ```json
        {
            "name": "John Doe",
            "email": "johndoe@test.com",
            "updated_at": "2021-06-07T11:45:34.000000Z",
            "created_at": "2021-06-07T11:45:34.000000Z",
            "id": 1
        }
        ```

### api/v1/users/login
- Description: Login a user.
- Method: `POST`
- Needs authentication: `false`
- Parameters:
    - `email`
        - Description: User email.
        - Type: `string`
    - `password`
        - Description: User password.
        - Type: `string`
- Example:
    - Input:
        ```json
        {
            "email": "johndoe@test.com",
            "password": "123456"
        }
        ```
    - Output:
        - Status: `200`
        - Response:
        ```json
        {
            "access_token": "1|QLbN4oOjoSMPaJcYtqP3R4NnvRhFeqzYnlDusOv8"
        }
        ```

### api/v1/users/tasks
- Description: List all active tasks.
- Method: `GET`
- Needs authentication: `true`
- Parameters:
- Example:
    - Input:
        ```
        ```
    - Output:
        - Status: `200`
        - Response:
        ```json
        [
            {
                "id": 1,
                "user_id": 1,
                "title": "First Task",
                "description": "Description",
                "completed_at": null,
                "deleted_at": null,
                "created_at": "2021-06-07T15:02:12.000000Z",
                "updated_at": "2021-06-07T15:45:07.000000Z"
            },
            {
                "id": 2,
                "user_id": 2,
                "title": "Second Task",
                "description": null,
                "completed_at": "2021-06-07T19:05:45.000000Z",
                "deleted_at": null,
                "created_at": "2021-06-07T15:05:45.000000Z",
                "updated_at": "2021-06-07T15:05:45.000000Z"
            }
        ]
        ```

### api/v1/users/{user_id}/tasks
- Description: List all user active tasks.
- Method: `GET`
- Needs authentication: `true`
- Parameters:
    - `{user_id}`
        - Description: User ID.
        - Type: `integer`
- Example:
    - Input:
        ```
        ```
    - Output:
        - Status: `200`
        - Response:
        ```json
        [
            {
                "id": 1,
                "user_id": 1,
                "title": "First Task",
                "description": "Description",
                "completed_at": null,
                "deleted_at": null,
                "created_at": "2021-06-07T15:02:12.000000Z",
                "updated_at": "2021-06-07T15:45:07.000000Z"
            }
        ]
        ```

### api/v1/users/{user_id}/tasks
- Description: Create a task for a user.
- Method: `POST`
- Needs authentication: `true`
- Parameters:
    - `{user_id}`
        - Description: User ID.
        - Type: `integer`
    - `title`
        - Description: Title of the task.
        - Type: `string`
    - `description`
        - Description: Description of the task.
        - Type: `string`
- Example:
    - Input:
        ```json
        {
            "title": "My Title",
            "description": "My description."
        }
        ```
    - Output:
        - Status: `200`
        - Response:
        ```json
        {
            "title": "My Title",
            "description": "My description.",
            "user_id": 1,
            "updated_at": "2021-06-07T15:12:07.000000Z",
            "created_at": "2021-06-07T15:12:07.000000Z",
            "id": 23
        }
        ```

### api/v1/users/{user_id}/tasks/{task_id}
- Description: Get an active task details.
- Method: `GET`
- Needs authentication: `true`
- Parameters:
    - `{user_id}`
        - Description: User ID.
        - Type: `integer`
    - `{task_id}`
        - Description: Task ID.
        - Type: `integer`
- Example:
    - Input:
        ```
        ```
    - Output:
        - Status: `200`
        - Response:
        ```json
        {
            "id": 1,
            "user_id": 1,
            "title": "My Task",
            "description": "My description.",
            "completed_at": null,
            "deleted_at": null,
            "created_at": "2021-06-07T21:54:29.000000Z",
            "updated_at": "2021-06-07T21:54:29.000000Z"
        }
        ```

### api/v1/users/{user_id}/tasks/{task_id}
- Description: Update an active task.
- Method: `PUT`
- Needs authentication: `true`
- Parameters:
    - `{user_id}`
        - Description: User ID.
        - Type: `integer`
    - `{task_id}`
        - Description: Task ID.
        - Type: `integer`
    - `user_id`
        - Description: New user.
        - Type: `integer`
    - `title`
        - Description: New title.
        - Type: `string`
    - `description`
        - Description: New description.
        - Type: `string`
- Example:
    - Input:
        ```json
        {
            "user_id": 2,
            "title": "New Title",
            "description": "New description."
        }
        ```
    - Output:
        - Status: `204`

### api/v1/users/{user_id}/tasks/{task_id}
- Description: Delete an active task.
- Method: `DELETE`
- Needs authentication: `true`
- Parameters:
    - `{user_id}`
        - Description: User ID.
        - Type: `integer`
    - `{task_id}`
        - Description: Task ID.
        - Type: `integer`
- Example:
    - Input:
        ```
        ```
    - Output:
        - Status: `204`

### api/v1/users/{user_id}/tasks/{task_id}/complete
- Description: Complete an active task.
- Method: `POST`
- Needs authentication: `true`
- Parameters:
    - `{user_id}`
        - Description: User ID.
        - Type: `integer`
    - `{task_id}`
        - Description: Task ID.
        - Type: `integer`
- Example:
    - Input:
        ```
        ```
    - Output:
        - Status: `204`

## Notes:
1. Host (port configurable in `docker-compose.yml`):
```
http://localhost:8080
```

2. MySQL access (configurable in `docker-compose.yml`):
```shell
docker exec -it tasks-api_mysql mysql -utasks-api -psecret -Dtasks-api
```

3. Run tests:
```shell
docker exec -t tasks-api_php ./vendor/bin/phpunit
```
