all:

run:
	php -S 127.0.0.1:1080

sass-watch:
	gulp sass:watch

install:
	composer install
	npm install