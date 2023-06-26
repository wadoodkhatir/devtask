# devtask

## Installation

step 1:  clone project.
step 2: open cmd run command (composer update).it will update your laravel dependency.
step 3. run apatch serve 
step 4. run command  (npm update). it will update vue js dependency.
step 5. run command (php artisan migrate). it will create db and migrate requried table in db.
step 5. update env email hosting configuration. like mailhost ,mailfrom etc.
step 6 . run command (php artisan optimize:clear). it will clear you cache file
step 7. its final run command (php artisan serve). then run http://127.0.0.1:8000

## tools
vue js is a front end Framework and Laravel has a backend. Loing authentication has done by sanctum. its token base authentication.
on the Backend there are different api they respone to the front end. its single page application. it will send and back request trough apis. the page will be never be loaded. 
## Features

this project have dashboard login,registraion ,reset password by opt by using email address.
step 1. first admin will login to dashboard
step 2. just create generate . it will create generate links and send to that email.the customer will recieved email . and submit their survay.the admin can check all the survay records.
