Symfony Demo Application
========================

The goal here is to give u a basic summury example of what is possible with this framework

What you can learn
------------------

 * Making an API DB CRUD, the maker bundle, allowing CORS, Request body validation.
 * Dependency injection: repositories, object manager, PARAM converter and services(image uploader)
 * Making, styling, Handling and adding Validation constraints to TWIG forms.
 * Using Faker Fixtures to generate Random database data.
 * Doctrine Database Assert constraints, relations and Solving the circular reference error.
 * Upload images to the public folder.
 * Making a service to centralize logic, and keep a clean controller.
 * getting and setting up the session.
 * User authentication, the make:user Security command.  

Requirements
------------

  * PHP 7.1.3 or higher;
  * PDO-SQLite PHP extension enabled;
  * Xampp or similar solution;
  * and the usual Symfony application requirements;

Usage
-----

<!-- There's no need to configure anything to run the application.  -->

If you have installed the [Symfony client][4] binary, run this command to run the built-in
web server and access the application in your browser at <http://localhost:8000>:

```bash
$ cd my_project/
$ symfony serve
```

If you don't have the Symfony client installed, run `php bin/console server:run`.
Alternatively, you can configure a web server like Nginx or Apache to run
the application.
