## Muhasabah API

This project is the backend or the server side of this [primary project](https://github.com/basithdjunaedi/MuhasabahApps)

To make sure the android project works. You must install this project and running the server in the same network as your client app. Before installing this project, make sure your computer environment matches this [laravel requirements](https://laravel.com/docs/5.2/installation#server-requirements)

If you have installed the server requirements, then its time to install this project!

- run `git clone https://github.com/ibnujakaria/muhasabah-api.git` on your command line
- cd muhasabah-api
- run `composer update`. Wait? Have you installed composer? If no, check [this](https://getcomposer.org/download/) out to install composer
- create new database and named it with something familiar with this project
- on terminal, copy file .env.example to be .env by executing command -> `cp .env.example .env`
- edit the .env file and fill it up with your environment settings such as db_name, db_username, db_password and any others by doing `nano .env`
- if everything corrects, just do `php artisan migrate` on terminal
- and `php artisan db:seed`
- now check your database and you should see some tables and data sample over there
- if everything works good, move to the public directory by running `cd public`
- Now, start your php server at the ip in your network (check your ifconfig), by running `php -S your-ip-address:8080`
- Open your android app, on the API class, change the API_BASE_URL to your ip address

## License

This Muhasabah API is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
