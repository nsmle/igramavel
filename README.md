# Igramapi
An unofficial Instagram RESTful API. easy  to fetch any feed and interact with Instagram (like, follow, etc.) with JWT implementation.

> **Warning**
> This project fully uses the [pgrimaud/instagram-user-feed](https://github.com/pgrimaud/instagram-user-feed) library, which has been modified on [nsmle/instagram-user-feed](https://github.com/nsmle/instagram-user-feed) and has not been merged. Please consider using the [main library](https://github.com/pgrimaud/instagram-user-feed) once the [pull request](https://github.com/pgrimaud/instagram-user-feed/pull/304) is approved and merged.

## Information
If you login with your instagram credentials on [/api/auth/login](https://github.com/nsmle/igramapi#login-with-instagram-credentials) and get `Checkpoint required, please provide IMAP credentials to process authentication` error like problem [#1](https://github.com/nsmle/igramapi/issues/1) over and over again. Consider using an [alternative login](https://github.com/nsmle/igramapi#login-with-instagram-cookie-sessionid) with the Instagram sessionid cookie which you can get in [this tutorial](https://wpautomatic.com/how-to-get-instagram-session-id/) or [this one](https://skylens.io/blog/how-to-find-your-instagram-session-id).

## Support <sub><sup>:heart:</sup></sub>
If you like and find this app useful, please give your support by starring in this repository, or make a donation via github sponsors, paypal, etc. :pray: :heartpulse:
<details>
  <summary>
    &nbsp;&nbsp;
    <b>nsmle</b>
  </summary>
  <br>
  <ul>
    <li>
      <a href="https://github.com/sponsors/nsmle">
        <p>Github Sponsors<p>
      </a>
    </li>
    <li>
      <a href="https://www.paypal.me/nsmle">
        <p>Paypal<p>
      </a>
    </li>
    <li>
      <a href="https://saweria.co/nsmle">
        <p>Saweria<p>
      </a>
    </li>
  </ul>
</details>
<details>
  <summary>
    &nbsp;&nbsp;
    <b>pgrimaud</b>
  </summary>
  <br>
  <ul>
    <li>
      <a href="https://github.com/sponsors/pgrimaud">
        <p>Github Sponsors<p>
      </a>
    </li>
    <li>
      <a href="https://www.paypal.me/grimaudpierre">
        <p>Paypal<p>
      </a>
    </li>
  </ul>
</details>

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
| `POST`      | [/api/auth/login/alternative](https://github.com/nsmle/igramapi#login-with-instagram-cookie-sessionid) | No |
| `GET`       | [/api/profile](https://github.com/nsmle/igramapi#get-logged-in-user-profile) | Yes |
| `GET`       | [/api/profile/{userId}](https://github.com/nsmle/igramapi#get-profile-by-user-id-or-username) | Yes |

> **Note**
> Replace `<BASEURL>` in example with your app base url.
> Or you can also replace it with [https://igramapi.herokuapp.com/](https://igramapi.herokuapp.com/) as an illustration when in production.

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
    curl -X POST <BASEURL>/api/auth/login/alternative -H "Content-Type: application/json" -d '{"name": "sessionid", "value": "YOUR_INSTAGRAM_SESSIONID_VALUE", "domain": ".instagram.com", "path": "/", "expires": "YOUR_INSTAGRAM_SESSIONID_EXPIRES"}'
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
    curl -X GET <BASEURL>/api/profile -H "Authorization: Bearer {token}" -H "Content-Type: application/json"
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
    curl -X GET <BASEURL>/api/profile/{userId|username} -H "Authorization: Bearer {token}" -H "Content-Type: application/json"
    ```


# Feedback
I currently made this project for personal purposes. I decided to share it here to help anyone with the same needs.
If you have any feedback to improve it, You found a bug, You need a new feature/endpoint.
You can [create an issue](https://github.com/nsmle/igramapi/issues) if needed and feel free to make a suggestion, or [open a PR](https://github.com/nsmle/igramapi/pulls)!

# License
Licensed under the terms of the [MIT License](https://github.com/nsmle/igramapi/blob/main/LICENSE).