# GitHub test task
This is a repository containing GitHub API test task.

## Deployment
+ Clone repository to your directory.
+ Create .env.local file with your GitHub username and Access Token. Take a look at .env file as example.
+ For tests create .env.test.local, use .env.test file as example
+ Run the following command to up environment:
```sh
        $ docker-compose up -d --build
```
+ To start using the App run the following command:
```sh
        $ docker-compose exec app composer install
```
+ To run "create repository" CLI command run the following command:
```sh
        $ docker-compose exec app php bin/console app:create-repository {Name}
```
+ To run "delete repository" CLI command run the following command:
```sh
        $ docker-compose exec app php bin/console app:remove-repository {Name}
```

+ To access repository endpoint navigate to http://localhost:8080/github/repository URL. Username set in .env.local will be used as standart.
  If you want to filter by username try this route: http://localhost:8080/github/repository?username=randomUser
+ If you want to see OpenApi follow this link: http://localhost:8080/api/docs
+ To run tests run the following command:
```sh
        $ docker compose exec -it app composer test
```
+ If you want code format command run, run this instruction:
```sh
        $ composer format
```