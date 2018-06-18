# µ-MVC, a micro framework for learning purposes

µ-MVC is a microframework for learning MVC design pattern and object-oriented programming. This was built for my job at Access Code School, for my students.

Current version : 1.1.1

## Prerequisites

* PHP >= 7.1.3
* Composer
* NodeJS / NPM

## Installation

```
git clone git@gitlab.com:synyster0fa7x/micro-mvc.git
cd micro-mvc/
cp .env.dist .env
composer install
npm install
```

Don't forget to fill `.env` with yout database credentials

## Commands

* `make sass:watch`: build your CSS from SASS files live
* `gulp sass`: build your CSS from SASS files
* `make run`: launch built-in PHP server 

## Report a bug

Open an issue

## License

MIT