# Order Payment API – Laravel

This project implements a simple yet clean **Order Payment API** using **Laravel**, designed to demonstrate:

-   Transaction handling (`DB::transaction`)
-   Model relationships
-   Clean code structure
-   JSON API responses (success/error)

---

## Features

-   Change order status from `pending` → `paid`
-   Update user points based on order amount
-   Use transactions for data safety
-   Return consistent JSON responses

---

## Technologies Used

-   **Laravel 10**
-   **MySQL**
-   **PHP 8.2+**

---

## Installation

1. **Clone the repo**

    ```bash
    git clone https://github.com/Moelnabawy8/order-task.git
    cd order-task
    ```

2. **Install dependencies**

    ```bash
    composer install
    ```

3. **Set up `.env`**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Run migrations**

    ```bash
    php artisan migrate
    ```

5. **(Optional)** Seed some test data:

    ```bash
    php artisan tinker
    User::create(['name' => 'Mo', 'email' => 'mo@test.com', 'password' => bcrypt('123456'), 'points' => 0]);
    Order::create(['user_id' => 1, 'total_price' => 150, 'status' => 'pending']);
    ```

---

## API Endpoint

### Pay Order

**URL:**

```
POST /api/orders/{order}/pay
```

**Description:**
Marks an order as “paid” and increases user points.
If `total_price >= 100`, the user gets `+10` bonus points.

**Example Request:**

```
POST http://127.0.0.1:8000/api/orders/1/pay
```

**Example Response:**

```json
{
    "message": "Payment processed successfully.",
    "order": {
        "id": 1,
        "user_id": 1,
        "total_price": 150,
        "status": "paid"
    },
    "user_points": 160
}
```

---

## Code Explanation

### `OrderController@pay`

-   Checks if order status is `pending`
-   Starts a database transaction
-   Updates order status to `paid`
-   Returns formatted JSON success or error response

## 🪪 License

This project is open-source and available under the [MIT License](LICENSE).
