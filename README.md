
# Igramapi
An unofficial Instagram RESTful API. easy  to fetch any feed and interact with Instagram (like, follow, etc.) with JWT implementation.

> **Warning**
> This project fully uses the [pgrimaud/instagram-user-feed](https://github.com/pgrimaud/instagram-user-feed) library, which has been modified on [nsmle/instagram-user-feed](https://github.com/nsmle/instagram-user-feed) and has not been merged. Please consider using the [main library](https://github.com/pgrimaud/instagram-user-feed) once the [pull request](https://github.com/pgrimaud/instagram-user-feed/pull/304) is approved and merged.


# Installation
```
git clone https://github.com/nsmle/igramapi.git
```

# Usage
- Open your terminal and go to your working directory.
- Clone this repository
  ```bash
  git clone https://github.com/nsmle/igramapi.git
  ```
- Go to folder
  ```bash
  cd igramapi
  ```
- Install dependencies
  ```bash
  composer install
  ```
- Create environment variable
  ```bash
  cp .env.example .env
  ```
- Generate key inside `.env` file 
  ```bash
  php artisan key:generate
  ```
- Generate `JWT_SECRET` key in `.env` file
  ```bash
  php artisan jwt:generate-key
  ```
- Start local server
  ```bash
  php artisan serve
  ```
- Open link `http://localhost:8000/api` to get all endpoints list on your browser


# Endpoints
| Method      | Endpoint    | Auth        |
| ----------- | ----------- | ----------- |
| `GET`       | [/api](https://github.com/nsmle/igramapi#get-all-list-of-api-endpoints) | No |
| `POST`      | [/api/auth/login](https://github.com/nsmle/igramapi#login-with-instagram-credentials) | No |

> **Note**
> Replace `<BASEURL>` in example with your app base url.

#### Get all list of api endpoints.
  - ENDPOINT
    ```
    /api
    ```
  - METHOD
    ```
    GET
    ```
  - EXAMPLE
    ```bash
    curl -X GET <BASEURL>/api
    ```
#### Login with instagram credentials.
  - ENDPOINT
    ```
    /api/auth/login
    ```
  - METHOD
    ```
    POST
    ```
  - BODY
    ```json
    {
        "username" : "YOUR_INSTAGRAM_USERNAME",
        "password" : "YOUR_INSTAGRAM_PASSWORD"
    }
    ```
  - EXAMPLE
    ```bash
    curl -X POST <BASEURL>/api/auth/login -H "Content-Type: application/json" -d '{"username": "YOUR_INSTAGRAM_USERNAME", "password": "YOUR_INSTAGRAM_PASSWORD"}'
    ```


# Feedback
I currently made this project for personal purposes. I decided to share it here to help anyone with the same needs.
If you have any feedback to improve it, You found a bug, You need a new feature/endpoint.
You can [create an issue](https://github.com/nsmle/igramapi/issues) if needed and feel free to make a suggestion, or [open a PR](https://github.com/nsmle/igramapi/pulls)!