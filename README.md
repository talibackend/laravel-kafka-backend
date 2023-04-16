#Documentation
According to requirements the projects contains some few endpoints for managing cart items and checkout. Below is a more detail explanation:

1. An add-to-cart endpoint was created to help user add/update cart items.
2. A delete-from-cart endpoint was created to help user delete cart items.
3. A checkout endpoint was created to help user checkout.

# How to run

1. Download the project by cloning this repository
2. Setup your `mysql` database.
3. Setup your `kafka broker`(it's easier to use docker)
4. Create a `.env` file in the root of the project and copy the content of `.env.example` into it.
5. Update the `.env` variables according to your local setup.
6. In the root of the project run 
```
php artisan migrate
```
7. Then run
```
php artisan serve
```
8.  Open another terminal and run 
```
php artisan consume:cart_items
```
9. Open another terminal and run
```
php artisan consume:checkout
```
10. You can not test the endpoints in the postman doc.

# Run Test
The run the automated tests, follow the steps below:
1. Setup a new `mysql` database.
2. Setup your `mysql` database.
3. Setup your `kafka broker`(it's easier to use docker)
4. Create a `.env.testing` file in the root of the project and copy the content of `.env.example` into it.
5. Update your `.env.testing` with the variables of your testing environment - **This is Very Important**.
6. Run
```
php artisan test
```
