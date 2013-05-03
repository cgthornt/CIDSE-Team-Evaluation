Capstone Project
================
Welcome to the capstone project!

[View the Old Project on Assembla!](https://www.assembla.com/spaces/asu-teamwork-evaluation/wiki) (this may contain many
useful links and documents).

## Setting up your Development Environment
Follow these steps to set up your project:

1. Download and install a source code editor. I suggest [Komodo Edit](http://www.activestate.com/komodo-edit)
1. Download, install and run [WAMP server](www.wampserver.com/en/)
1. Add PHP to your PATH environment variable (you know you did it right if `php -v` works in the command prompt)
1. Checkout the project from GitHub.
1. Install composer packages (shown below)
1. Create a MySQL database
1. Run any migrations (shown below)
1. Navigate to the `public` directory.


## Migrations
See the [Yii Guide](http://www.yiiframework.com/doc/guide/1.1/en/database.migration) on migrations for more information.

To run a migration:

    yiic migrate
    
To create a migration:

    yiic migrate new [name]
    
Migrations are saved in the `app/migrations` folder.

## Composer
This project uses [Composer](http://getcomposer.org/), which is a package manager for PHP. Composer allows for the addition of third-party packages in the code base
without adding extra files to the repository.

To install packages from composer, enter in the terminal:

    php composer.phar install
    
If you just added a new item package to composer:

    php composer.phar update
    
## Deployment
This project uses [Capistrano](https://github.com/capistrano/capistrano) for the deployment process, which is written in Ruby. Please read the capistrano documentation (or Google)
on how to set up capistrano on your machine.

To modify the deployment behavior, please modify the `config/deploy.rb' file.

To deploy the application:

    cap deploy

If you are migrating the deployment to a new server, you may need to do:

    cap deploy:setup
    
