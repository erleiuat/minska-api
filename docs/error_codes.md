# Possible Error Codes by Objects
If defined these Codes will return in is the "reason" Variable from an Error.


## User

| Code              | Type      | Endpoint      | Description   |
|----------         |---------- |----------     |----------     |
| email_in_use      | 400       | /user/create/ | The E-Mail used for Registration is already in use.   |
| password_invalid  | 400       | /user/create/ | The Password is not set/not complex enough.   |
