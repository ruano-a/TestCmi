# TestCmi

# Includes 

* Entities
- Article
- Comment
- Votes

* Basic fixtures (DataFixtures folder)

* Security against bots for comment submission, with google recaptcha v3

* Login via facebook

* You can answer to comments on "unlimited depth" (there is an example in second article)

* You can rate comments, with a downvote or upvote

* Commenting and voting is secured under firewall, you can't do it if you're not logged in

# How to install

```sh
yarn install
yarn encore dev
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force # IN DEVELOPMENT ONLY
php bin/console doctrine:fixtures:load
```

# Notes

* The error messages saying that you can't use facebook api in http don't matter in development mode
* The design not being the subject, and everything being done with a timelimit, the design hasn't been worked on at all
* The input to send a comment appears when you first display the comments