# TestCmi

# Includes 

* Entities
- Article
- Comment

* Basic fixtures (DataFixtures folder)

# How to install

```sh
yarn install
yarn encore dev
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force # IN DEVELOPMENT ONLY
php bin/console doctrine:fixtures:load
```

# Notes

* The error messages saying that you can't use facebook api in http doesn't matter in development mode

# TODO 
* indexs
* set api_fb_key in .env


* form submit (php)
* home page
* article page
* api provide article
* constraints
* login facebook