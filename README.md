<h1 align="center">UPEI Behavior Rating Tool</h1>
<p>
  <img alt="Version" src="https://img.shields.io/badge/version-1.0-blue.svg?cacheSeconds=2592000" />
  <a href="https://opensource.org/licenses/MIT" target="_blank">
    <img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-yellow.svg" />
  </a>
</p>

> The behavior rating tool is software pertinent to the Atlantic Canadian Veterinary College at UPEI. This software's aim is to improve students' knowledge on various animal behaviors.

## Table of Contents

- [1.0 Installation](#10-installation)
  * [1.1 Installing Git](#11-installing-git)
  * [1.2 Cloning the repository](#12-cloning-the-repository)
  * [1.3 Install Docker](#13-install-docker)
  * [1.4 Generating Environment Variables](#14-generating-environment-variables)
  * [1.5 Pull Relevant Composer Packages](#15-pull-relevant-composer-packages)
  * [1.6 Install relevant NPM packages](#16-install-relevant-npm-packages)
  * [1.7 Installing mkcert](#17-installing-mkcert)
  * [1.8 Generating an Encryption Key](#18-generating-an-encryption-key)
  * [1.9 Build the Dockerfile](#19-build-the-dockerfile)
  * [1.10 Migrate and Seed Database](#110-migrate-and-seed-database)
  * [1.11 Test your installation](#111-test-your-installation)
- [2.0 Development](#20-development)
  * [2.1 Creating Models](#21-creating-models)
  * [2.2 Writing Models](#22-writing-models)
  * [2.3 Blade Templating](#23-blade-templating)
- [3.0 Testing](#30-testing)
  * [3.1 Creating Tests](#31-creating-tests)
  * [3.2 Modifying Tests](#32-modifying-tests)
  * [3.3 Running Tests](#33-running-tests)
- [4.0 Naming Conventions](#40-naming-conventions)
  * [4.1 Branches](#41-branches)
  * [4.2 Classes](#42-classes)
  * [4.3 Commits](#43-commits)
  * [4.4 Versioning](#44-versioning)
- [X.0 Deploying](#x0-deploying)
- [Authors](#authors)
- [License](#---license)


## 1.0 Installation

Follow the instructions below to install the software onto your system for development.

### 1.1 Installing Git

Git can be downloaded and installed on any operating system from [their website](https://git-scm.com/). If you're interested in using a client then there are a couple of options:

**Software**
 * [Github Desktop](https://desktop.github.com/) [Mac, Windows]
 * [GitKraken](https://www.gitkraken.com/) [Linux, Mac, Windows]
 * [SourceTree](https://www.sourcetreeapp.com/) [Mac, Windows]

### 1.2 Cloning the repository

Cloning the repository is simple. Run the following command in the directory that you would like to house the project.

```sh
git clone https://github.com/CS48X0-Behavior-Tool-Project/behavior-rating-tool.git
```

### 1.3 Install Docker

In order to run our projects in similar environments to eliminate development inconsistencies, we're going to use Docker. Read more about it [here](https://docs.docker.com/get-started/overview/).

To install it for Mac or Windows, we can simply download Docker Desktop from the [Docker website](https://www.docker.com/products/docker-desktop).

If you plan on using Docker on Linux, you may like to check out [Docker's tutorial](https://docs.docker.com/engine/install/ubuntu/) on setting it up.

### 1.4 Generating Environment Variables

Docker utilizes environment files which can help keep private information from the Github - such as the database password. To create the actual environment file, simply run the command:

```sh
cp ./src/.env.example ./src/.env
```

Once the .env file is created, we need to replace an important line. This line is left as is in the .env.example file simply for CircleCI. However, on your system you will need to replace DB_HOST=127.0.0.1 with DB_HOST=mysql. Everything else should be left as is.

### 1.5 Pull Relevant Composer Packages

We'll need to install relevant Composer packages. These are not included in the Github repository for obvious reasons. To do this, run the command:

```sh
docker-compose run --rm composer update
```

This will pull all the required packages from Composer and they will be put into the *src/vendor* folder.

### 1.6 Install relevant NPM packages

NPM is also required for the project. To install the required packages, please run:

```sh
docker-compose run --rm npm install
docker-compose run --rm npm run dev
```

### 1.7 Installing mkcert [**DEPRECATED**]

[mkcert](https://github.com/FiloSottile/mkcert) is required if we want to enable https on our local machine, which improves the development experience. The mkcert Github (linked above) lists how to install mkcert in their documentation. However, windows is a bit different as it requires a package manager which you may not be familiar with, Scoop. Run the following commands below in Powershell as administrator:

```sh
Set-ExecutionPolicy RemoteSigned -scope CurrentUser
iwr -useb get.scoop.sh | iex
```

Once installed, close your Powershell instance and open it again. Then simply run:

```sh
scoop bucket add extras
scoop install mkcert
```

Navigate to the project root, (not the src folder, the root of the project itself, so you're in the folder *behavior-rating-tool*) and run mkcert -install. Then run the following command on Windows:

```sh
cd nginx; mkdir certs; cd certs; mkcert localhost;
```

For Linux:

```sh
cd nginx && mkdir certs && cd certs && mkcert localhost
```

You should now have locally certified your domain which means https will be enabled when accessing localhost. Be sure to set your directory back to the root directory, i.e.

```sh
cd ../..
```

### 1.8 Build the Dockerfile

Ensure that you're back in the root directory, *behavior-rating-tool*. To build the Dockerfile we can run the following command:

```sh
docker-compose up -d --build brt
```

This command will run the Docker container discretely in the background of your terminal and build all the necessary components. If you have already built the images before, then you only have to run:

```sh
docker-compose up -d
```

Keep in mind however, if you have changed something important in the docker-compose.yml or any of the Dockerfiles, you will have to reuse the build option.

### 1.9 Migrate and Seed Database

Before you start development, you need to migrate the database using the migrations generated by other developers in the *src/database/migrations* folder. Since our MySQL files are obviously not committed to the GitHub repository, we're not only going to want to generate the tables in the database but fill them with dummy test data too. We can do that with the following commands:

```sh
docker-compose exec brt php artisan migrate
docker-compose exec brt php artisan db:seed
```

To access and view the database for development purposes, the following commands should be followed:

```sh
docker-compose exec mysql /bin/sh
mysql -u laravel -p laraveldb
```
The password is "secret".

You will want to run the migrations to ensure all the tables are set up properly:

```sh
docker-compose exec brt php artisan migrate:refresh
```

Then you should populate the database using the Bouncer seeder so that an admin account can be created and logged in to:

```sh
docker-compose exec brt php artisan db:seed --class=BouncerSeeder
```

### 1.10 Run autoqueue for video resizing

The project currently uses Laravel's job system to automatically resize and scale down videos made in upload requests. Without this enabled, videos will still play fine, however, they will not be compressed meaning a lot more space will be taken up, which isn't particularly convenient. To start the job queue, run the following command:

```
docker-compose exec brt supervisord
```

### 1.11 Generating an Encryption Key

An encryption key is relatively important, and it's why we generate one instead of committing it to the Git repository - where it is vulnerable to attacks. To access the PHP environment, run the first command. From there, you should have access to Artisan (and other various PHP related commands). To generate a key, simply run the command:

```sh
docker-compose exec brt php artisan key:generate
```

If at any point in time Laravel complains you're missing a key - run this command again.


### 1.12 Test your installation

To check if the installation went successfully, go to http://localhost:8080 in your browser. If successful, you should see a landing page. Make sure it is http, and not https. Locally certified SSL with Apache is much more difficult to setup and is not worth the hassle. Most modern web browsers will attempt to take you to https:// unless you specify http://. So if you ever attempt to load the website and it doesn't appear, first double check that you typed:

```
http://localhost:8080
```

## 2.0 Development

The following category will explain the basics of development with Laravel. Laravel is a PHP framework known for its "automagical" capabilities. From an outside perspective, it's very confusing. If you want to explore it in full, then PHPStorm is your goto. If you're confident in your abilities or in your ability to solve problems then Visual Studio Code is still a great IDE.

### 2.1 Creating Models

Laravel includes a package called Eloquent, which is an ORM which has some of those "automagical" properties as discussed above. Models are an important part of development, they are what describe tables in PHP code.

To create a model is simple:

```sh
docker-compose exec brt php artisan make:model ModelName -a
```

Be sure to replace ModelName with the preferred name.

Thanks to an update in 2020, Laravel has included an option *-a* which eases the development process. The *-a* option will create a model, controller, seeder, migration, and factory for that class. However, if you wish to create these individually, that will be below:

Model
```sh
docker-compose exec brt php artisan make:model ModelName
```

Controller
```sh
docker-compose exec brt php artisan make:controller ControllerName
```

Seeder
```sh
docker-compose exec brt php artisan make:seeder SeederName
```

Migration
```sh
docker-compose exec brt php artisan make:migration MigrationName
```

Factory
```sh
docker-compose exec brt php artisan make:factory FactoryName
```

As you can see, the commands are very similar, and it's easy to see how intuitive Laravel is. This is the preferred method of creating these classes, as handtyped classes introduces human error which can go unspotted for days.

### 2.2 Writing Models

As stated before, models work as both datamodels as ORMs for the database simultaneously. These will be used extensively in the Blade templating engine and throughout the project itself. Check out the documentation for [Eloquent](https://laravel.com/docs/8.x/eloquent). Also, be sure to check out the src/vendor/app/Models/User.php file for a short example on what a model may look like.

### 2.3 Blade Templating

Thankfully, Laravel comes prepackaged with a great HTML templating engine known as Blade. Blade allows us to inject data into a websites view. You can see this in action in the *src/resources/views/welcome.blade.php* file. As you can imagine, this will be extremely useful. To read more about it, please check out the [Laravel documentation](https://laravel.com/docs/8.x/blade#:~:text=Blade%20is%20the%20simple%2C%20yet,PHP%20code%20in%20your%20templates.&text=Blade%20template%20files%20use%20the,in%20the%20resources%2Fviews%20directory.).

## 3.0 Testing

In the current project, we're going to have several ways to run tests. Every time you commit to the repository it will run automated tests through CircleCI. You will be alerted as to whether these tests have passed or failed. Below, you will find how to create tests, and how to run them.

### 3.1 Creating Tests

Creating tests is simple. When a test is created, it is placed by default in the *src/tests/Feature* folder. Feature tests are used to test if an entire feature works, it's in the name. For example, the ExampleTest.php feature tests to see if we get a 200 return code on the index of the website. To create a feature test:

```sh
docker-compose exec brt php artisan make:test ExampleTest
```

Be sure to replace the "Example" text with whatever text is necessary to describe that test.

Unit tests on the other hand are placed in the *src/tests/Unit* folder, and are created for the sole purpose of testing small individual components of the project, like database seeding. To create a unit test is very similar to creating a feature test:

```sh
docker-compose exec brt php artisan make:test ExampleTest --unit
```

The same applies to above, ensure that you replace the "Example" text with whatever text is necessary to describe that text.

### 3.2 Modifying Tests

As stated before, you can find tests in *src/vendor/Feature* or *src/vendor/Unit*. You can read more about PHPUnit assertions in their [documentation](https://phpunit.readthedocs.io/en/9.3/assertions.html).

There isn't much else to say, aside from the fact that if you want something to be registered as a test function, you must remember to preface the function with the keyword "test". See src/vendor/Unit/ExampleTest.php as an example.

### 3.3 Running Tests

We also have a manual method of testing, with a certain Composer package. PHPUnit is a flexible unit testing library that has been used for ages in PHP, it is the definition of tried and true. To run the tests that have been created:

```sh
docker-compose exec brt php ./vendor/bin/phpunit
```

The PHPUnit command will give a very succinct output, simply displaying how many tests passed successfully. If we want a more verbose output, then we can run the Artisan test command:

```sh
docker-compose exec brt php artisan test
```

## 4.0 Naming Conventions

The following section will detail the naming convention schemes that will be implemented in the project, whether it be for classes, branches, issues, releases, or commits. It is important to note that most of the topics covered for naming conventions are also mentioned in the [North design and development handbook](https://github.com/north/north).

### 4.1 Branches

Naming branches isn't a horribly complex task. In fact, it will be describable in a few short sentences. The North design handbook above mentions "feature branches" which are a subset of what we will be using.

In particular, when naming a branch, first prefix it with the task at hand. If you are implementing a whole feature, prefix it with feature. If you are fixing a small bug, then we would prefix it with fix. Finally, if you are updating documentation, prefix it with docs. After these prefixes, append a slash and then the name of the actual branch. This allows some programs like Fork or SourceTree to create tree like groupings out of the prefix names. It also keeps everything much more organized. Some examples below:

 * feature/quiz-submission
 * fix/erronius-migration
 * docs/conventions
 * refactor/webserver-swap

### 4.2 Classes

Thankfully, most of the class naming conventions are resolved already through Laravel. As stated in section 2.1, there is an option whilst creating a model that will automatically create our controllers, factories, etc. for us. So, when naming a model, just name it what it is, for instance, if were to create a user model:

```sh
docker-compose run --rm artisan make:model User -a
```

Automatically, a UserController, UserFactory, UserSeeder, and UserMigration classes/files are created. Hence, this is the naming conventions we will follow. For more information, please read the [Laravel best practices repo](https://github.com/alexeymezenin/laravel-best-practices).

### 4.3 Commits

The commit conventions that we'll be following for the project are detailed in the following [ConventionalCommits documentation](https://www.conventionalcommits.org/en/v1.0.0/).

### 4.4 Versioning

Similarly to commits, we'll be following the [SemVer versioning style](https://semver.org/).


## 5.0 Debugging

If you come across problems while developing, come to this list of possible solutions

### 5.1 Permissed Denied Error

This can occur when pulling a new branch from the repository or if the Dockerfiles are modified. It's a very simple fix, simply run

```sh
docker exec -it brt /bin/sh
chown -R www-data:www-data .
```

### 5.2 Missing Packages

Similarly to 5.1, you may get this error when pulling down from the Github repository. Since vendor packages aren't uploaded to the repository, it is necessary to run this command any time new packages have been added. To fix this, we simply run the command:

```sh
docker-compose run --rm composer install
```

If we're missing any packages, this will make sure to install the ones listed in the composer.lock file.

### 5.3 VSCode Requesting Save Permissions

If you are on a Linux based system you may encounter this error. Essentially, when you set the ownership in debugging 5.1 you exclude your current user from write permissions. To resolve this there are a multitude of steps to follow, given from [this Stackoverflow answer](https://stackoverflow.com/a/61939446/9861242).


## X.0 Deploying

TBD... Need More information and resources provided to us first.

***

## Authors

* Chris MacDonald
* Amanda Isenor
* Erik Moraru
* Teng Liu
* Zak McLure

## License

This project is [MIT](https://opensource.org/licenses/MIT) licensed.
