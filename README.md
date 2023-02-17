# Igramapi
![Igramapi](/public/images/banner.png)

![JWT Compatible](https://jwt.io/img/badge-compatible.svg)
![Instagram](https://img.shields.io/badge/Instagram-%23E4405F.svg?style=for-the-badge&logo=Instagram&logoColor=white)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![React](https://img.shields.io/badge/react-%2320232a.svg?style=for-the-badge&logo=react&logoColor=%2361DAFB)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)

An unofficial Instagram RESTful API. easy  to fetch any feed and interact with Instagram (like, follow, etc.) with JWT token implementation.

## Information
If you login with your instagram credentials on [/auth/login](https://github.com/nsmle/igramapi#login-with-instagram-credentials) and get `Checkpoint required, please provide IMAP credentials to process authentication` error like problem [#1](https://github.com/nsmle/igramapi/issues/1) over and over again. Consider using an [alternative login](https://github.com/nsmle/igramapi#login-with-instagram-cookie-sessionid) with the Instagram sessionid cookie which you can get in [this tutorial](https://wpautomatic.com/how-to-get-instagram-session-id/) or [this one](https://skylens.io/blog/how-to-find-your-instagram-session-id).

> **Warning**
> This project uses the [nsmle/instagram-user-feed](https://github.com/nsmle/instagram-user-feed) library instead of [pgrimaud/instagram-user-feed](https://github.com/pgrimaud/instagram-user-feed) now onwards. Because some of the [features developed](https://github.com/pgrimaud/instagram-user-feed/pull/330) and used in this project have not been accepted and I'm still trying to make changes to some of the features that might break.
> 
> If you are having trouble with this, you can delete lines [35-60](https://github.com/nsmle/igramapi/blob/main/composer.json#L35-L60) and replace line [69](https://github.com/nsmle/igramapi/blob/main/composer.json#L69) to:
> ```json
> "pgrimaud/instagram-user-feed": "^6.16.4"  // Or to a higher version, See: https://github.com/pgrimaud/instagram-user-feed/releases 
> ```
> in your `composer.json` file. Or if you have a solution to an existing problem, you can open an issue or make a pull request.

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
- Open link `http://localhost:8000/` to see available endpoints list

Please see your app [BASEURL](https://github.com/nsmle/igramapi/blob/main/.env.example#L5) to see documentations, or you can see [https://igramapi.fiki.tech/](https://igramapi.fiki.tech/). Or you can also read the [Endpoints](https://github.com/nsmle/igramapi#endpoints) bellow.

## Endpoints
See your BASEURL and custom your [APP_URL](https://github.com/nsmle/igramapi/blob/main/.env.example#L5) in `.env` file.

#### Base API Url
```bash
<BASEURL>/v1
```
`v1` is semantic version of this application in [.env](https://github.com/nsmle/igramapi/blob/main/.env.example#L6) file.

#### Paths
| Method      | Endpoint    | Auth        |
| ----------- | ----------- | ----------- |
| `POST`      | [/auth/login](https://github.com/nsmle/igramapi#login-with-instagram-credentials) | No |
| `POST`      | [/auth/login/alternative](https://github.com/nsmle/igramapi#login-with-instagram-cookie-session-id) | No |
| `GET`       | [/user](https://github.com/nsmle/igramapi#get-logged-in-user-profile) | Yes |
| `GET`       | [/user/{userId}](https://github.com/nsmle/igramapi#get-profile-by-user-id-or-username) | Yes |
| `POST`      | [/user/{userId}/follow](https://github.com/nsmle/igramapi#follow-user) | Yes |
| `POST`      | [/user/{userId}/unfollow](https://github.com/nsmle/igramapi#unfollow-user) | Yes |
| `GET`       | [/reels/{userId}](https://github.com/nsmle/igramapi#get-reels-of-user) | Yes |

> **Note**
> Replace `<BASEAPIURL>` in example with your app [base api url](https://github.com/nsmle/igramapi#base-api-url).
> You can also replace it with [https://igramapi.fiki.tech/v1](https://igramapi.fiki.tech/v1) as an illustration when in production.
>
> You can also send jwt token via cookie/query instead of token header. E.g in [Curl](https://curl.se/docs/http-cookies.html):
> ```bash
> curl -X <METHOD> "<BASEURL>/<VERSION>/<PATH>"
>      -H "Content-Type: <CONTENT_TYPE>"
>      -d "<DATA>"
>      -b "token=<YOUR_JWT_TOKEN>" 
> ```
> The jwt token contains the Instagram session id, csrf token cookie and along with some other information.


#### Login with instagram credentials.
- ENDPOINT
  ```
  /auth/login
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
  curl -X POST "<BASEAPIURL>/auth/login" -H "Content-Type: application/json" -d '{ "username": "YOUR_INSTAGRAM_USERNAME", "password": "YOUR_INSTAGRAM_PASSWORD" }'
  ```

#### Login with instagram cookie session id.
- ENDPOINT
  ```
  /auth/login/alternative
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
        "name": "sessionid",
        "domain"  : "YOUR_INSTAGRAM_SESSIONID_DOMAIN | .instagram.com",
        "path": "YOUR_INSTAGRAM_SESSIONID_PATH | /",
    }
    ```
- EXAMPLE
    ```bash
    curl -X POST "<BASEAPIURL>/auth/login/alternative" -H "Content-Type: application/json" -d '{ "name": "sessionid", "value": "YOUR_INSTAGRAM_SESSIONID_VALUE", "domain": ".instagram.com", "path": "/", "expires": "YOUR_INSTAGRAM_SESSIONID_EXPIRES" }'
    ```

#### Get logged in user profile.
  - ENDPOINT
    ```
    /user
    ```
  - METHOD
    ```
    GET
    ```
  - EXAMPLE
    ```bash
    curl -X GET "<BASEAPIURL>/user" -H "Authorization: Bearer {token}" -H "Content-Type: application/json"
    ```

#### Get profile by user id or username.
  - ENDPOINT
    ```
    /user/{userId|username}
    ```
  - METHOD
    ```
    GET
    ```
  - EXAMPLE
    ```bash
    curl -X GET "<BASEAPIURL>/user/{userId|username}" -H "Authorization: Bearer {token}" -H "Content-Type: application/json"

#### Follow a user.
  - ENDPOINT
    ```
    /user/{userId|username}/follow
    ```
  - METHOD
    ```
    POST
    ```
  - EXAMPLE
    ```bash
    curl -X GET "<BASEAPIURL>/user/{userId|username}/follow" -H "Authorization: Bearer {token}" -H "Content-Type: application/json"

#### Unfollow a user.
  - ENDPOINT
    ```
    /user/{userId|username}/unfollow
    ```
  - METHOD
    ```
    POST
    ```
  - EXAMPLE
    ```bash
    curl -X GET "<BASEAPIURL>/user/{userId|username}/unfollow" -H "Authorization: Bearer {token}" -H "Content-Type: application/json"
    ```

#### Get reels of user.
  - ENDPOINT
    - get reels
      ```
      /reels/{userId|username}
      ```
    - get next reels
      ```
      /reels/{userId|username}?cursor={maxId}
      ```
  - METHOD
    ```
    GET
    ```
  - EXAMPLE
    ```bash
    curl -X GET "<BASEAPIURL>/reels/{userId|username}" -H "Authorization: Bearer {token}" -H "Content-Type: application/json"
    ```

## Contributions
Contributions of any kind welcome!

## Feedback
I currently made this project for personal purposes. I decided to share it here to help anyone with the same needs.
If you have any feedback to improve it, You found a bug, You need a new feature/endpoint.
You can [create an issue](https://github.com/nsmle/igramapi/issues) if needed and feel free to make a suggestion, or [open a PR](https://github.com/nsmle/igramapi/pulls)!

## License
Licensed under the terms of the [MIT License](https://github.com/nsmle/igramapi/blob/main/LICENSE).
Following the [instagram-user-feed License](https://github.com/pgrimaud/instagram-user-feed/blob/master/LICENSE).
Use it wisely and don't abuse it!