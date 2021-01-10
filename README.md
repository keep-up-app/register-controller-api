# Register Controller Endpoint

## Overview

This repo contains the user source code for the register controller. The sole purpose of this endpoint is to act as a proxy server for handling user-registrations within the application.

## Endpoint URIs

* ### **`/`**

##### Description: Registers new users

> * http verb: *`POST`*
> * reponse code: `200`
> * response type: *JSON*
>
> Returns a User object (see [user endpoint][user-repo] for more information)

##### Url Body Paramaters:

|param|description|type|default|
|---:|---|:---:|:---:| 
| **`email`** | A valid email of the user account to login with |*string* |
| **`password:first`** | A valid password chosen by the user |*string* |
| **`password:second`** | The repeated password to confirm the chosen password |*string* |

#### Usage

Register with credentials:
* `email`: **example@email.com**
* `password`: **alphanumerical_1234**

Will yeild:

```json
{
    "auth": {
        "enabled": false
    },
    "steamid": null,
    "_id": "81a0c685-1156-4a55-9855-3bbed57260a8",
    "username": "ISLAMIC-FISH",
    "email": "example@email.com",
    "token": "03f7a63d4225c2d879c4c53167745d",
    "created_at": "Sun Jan 10 2021"
}
```


[user-repo]: https://github.com/noahgreff/user-api-endpoint/


