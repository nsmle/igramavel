# Igramapi
![Igramapi](/public/images/banner.png)

![JWT Compatible](https://jwt.io/img/badge-compatible.svg)
![Instagram](https://img.shields.io/badge/Instagram-%23E4405F.svg?style=for-the-badge&logo=Instagram&logoColor=white)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![React](https://img.shields.io/badge/react-%2320232a.svg?style=for-the-badge&logo=react&logoColor=%2361DAFB)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)

An unofficial Instagram RESTful API. easy  to fetch any feed and interact with Instagram (like, follow, etc.) with JWT implementation.

## Information
If you login with your instagram credentials on [/api/auth/login](https://github.com/nsmle/igramapi#login-with-instagram-credentials) and get `Checkpoint required, please provide IMAP credentials to process authentication` error like problem [#1](https://github.com/nsmle/igramapi/issues/1) over and over again. Consider using an [alternative login](https://github.com/nsmle/igramapi#login-with-instagram-cookie-sessionid) with the Instagram sessionid cookie which you can get in [this tutorial](https://wpautomatic.com/how-to-get-instagram-session-id/) or [this one](https://skylens.io/blog/how-to-find-your-instagram-session-id).

## Support <sub><sup>:heart:</sup></sub>
If you like and find this app useful, please give your support by starring in this repository, or make a donation via [Saweria](https://saweria.co/nsmle) or : 

[![Github-sponsors](https://img.shields.io/badge/sponsor-30363D?style=for-the-badge&logo=GitHub-Sponsors&logoColor=#EA4AAA)](https://github.com/sponsors/nsmle)
[![PayPal](https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://www.paypal.me/nsmle)

## Special Thanks <sub><sup>:pray:</sup></sub>
A big thank you to [Pierre Grimaud](https://github.com/pgrimaud) for creating a very useful [instagram-user-feed](https://github.com/pgrimaud/instagram-user-feed) library.

Help contribute to solving [problems in instagram-user-feed](https://github.com/pgrimaud/instagram-user-feed/issues) or [sponsors to Pierre Grimaud](https://github.com/sponsors/pgrimaud) to keep this beloved library alive and well <sub><sup>:pray:</sup></sub>.

## What's next ?
Please take a look at the [Igramapi Roadmap](https://github.com/users/nsmle/projects/2) to see what features will be implemented next or what fixes are coming.

## Installation
```
git clone https://github.com/nsmle/igramapi.git
```

## Usage
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
- Generate app key inside `.env` file 
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


## Endpoints
| Method      | Endpoint    | Auth        |
| ----------- | ----------- | ----------- |
| `GET`       | [/api](https://github.com/nsmle/igramapi#get-all-list-of-api-endpoints) | No |
| `POST`      | [/api/auth/login](https://github.com/nsmle/igramapi#login-with-instagram-credentials) | No |
| `POST`      | [/api/auth/login/alternative](https://github.com/nsmle/igramapi#login-with-instagram-cookie-sessionid) | No |
| `GET`       | [/api/profile](https://github.com/nsmle/igramapi#get-logged-in-user-profile) | Yes |
| `GET`       | [/api/profile/{userId}](https://github.com/nsmle/igramapi#get-profile-by-user-id-or-username) | Yes |
| `GET`       | [/api/reels/{userId}](https://github.com/nsmle/igramapi#get-reels-of-user) | Yes |

> **Note**
> Replace `<BASEURL>` in example with your app base url.
>
> You can also replace it with [https://igramapi.herokuapp.com/](https://igramapi.herokuapp.com/) as an illustration when in production.

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
    curl -X GET "<BASEURL>/api"
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
    curl -X POST "<BASEURL>/api/auth/login" -H "Content-Type: application/json" -d '{"username": "YOUR_INSTAGRAM_USERNAME", "password": "YOUR_INSTAGRAM_PASSWORD"}'
    ```

#### Login with instagram cookie sessionid.
  - ENDPOINT
    ```
    /api/auth/login/alternative
    ```
  - METHOD
    ```
    POST
    ```
  - BODY
    - Required
       ```json
       {
           "value"   : "YOUR_INSTAGRAM_SESSIONID_VALUE",
           "expires" : "YOUR_INSTAGRAM_SESSIONID_EXPIRES"
       }
       ```
    - Optional
       ```json
       {
           "name"    : "sessionid",
           "domain"  : "YOUR_INSTAGRAM_SESSIONID_DOMAIN|.instagram.com",
           "path"    : "YOUR_INSTAGRAM_SESSIONID_PATH|/",
       }
       ```
  - EXAMPLE
    ```bash
    curl -X POST "<BASEURL>/api/auth/login/alternative" -H "Content-Type: application/json" -d '{"name": "sessionid", "value": "YOUR_INSTAGRAM_SESSIONID_VALUE", "domain": ".instagram.com", "path": "/", "expires": "YOUR_INSTAGRAM_SESSIONID_EXPIRES"}'
    ```

#### Get logged in user profile.
  - ENDPOINT
    ```
    /api/profile
    ```
  - METHOD
    ```
    GET
    ```
  - EXAMPLE
    ```bash
    curl -X GET "<BASEURL>/api/profile" -H "Authorization: Bearer {token}" -H "Content-Type: application/json"
    ```

#### Get profile by user id or username.
  - ENDPOINT
    ```
    /api/profile/{userId|username}
    ```
  - METHOD
    ```
    GET
    ```
  - EXAMPLE
    ```bash
    curl -X GET "<BASEURL>/api/profile/{userId|username}" -H "Authorization: Bearer {token}" -H "Content-Type: application/json"
    ```

#### Get reels of user.
  - ENDPOINT
    - get reels
      ```
      /api/reels/{userId|username}
      ```
    - get next reels
      ```
      /api/reels/{userId|username}?cursor={maxId}
      ```
  - METHOD
    ```
    GET
    ```
  - EXAMPLE
    ```bash
    curl -X GET "<BASEURL>/api/reels/{userId|username}" -H "Authorization: Bearer {token}" -H "Content-Type: application/json"
    ```


## Feedback
I currently made this project for personal purposes. I decided to share it here to help anyone with the same needs.
If you have any feedback to improve it, You found a bug, You need a new feature/endpoint.
You can [create an issue](https://github.com/nsmle/igramapi/issues) if needed and feel free to make a suggestion, or [open a PR](https://github.com/nsmle/igramapi/pulls)!

## License
Licensed under the terms of the [MIT License](https://github.com/nsmle/igramapi/blob/main/LICENSE).
Following the [instagram-user-feed License](https://github.com/pgrimaud/instagram-user-feed/blob/master/LICENSE).
Use it wisely and don't abuse it!