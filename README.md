# Project Documentation

# Introduction

This document provides instructions for setting up the project using Laravel Sail. The project is designed to offer a seamless shopping experience for eyewear, supporting dynamic pricing in multiple currencies and real-time stock management. It uses Laravel v11 and PHP v8.2 for the backend, with a focus on RESTful API design to serve a frontend that could be built with a modern JavaScript framework like Vue.js or React.

### Key Features

- Dynamic pricing management with multi-currency support to cater to a global market.
- Real-time inventory updates to prevent overselling.
- Extensive API functionalities to manage products (frames and lenses) and customize user interactions.

### Completed Tasks:
- Developed RESTful API endpoints for both admin and user functionalities:
  - Admin can add and list frames and lenses with dynamic pricing in multiple currencies.
  - Users can view available frames and lenses, and create custom glasses combinations.
- Implemented a database with `users`, `frames`, `lenses`, `currencies`, and `prices` tables to support dynamic pricing and stock management.
- Introduced functionality for dynamic price conversion based on user-selected currency using the `X-Currency` header.
- Enabled users to create custom glasses, automatically adjusting stock and calculating prices.
- Added robust input validation to ensure data integrity for transactions (e.g., ensuring sufficient stock and valid currency codes).
- Created seeders for populating the database with initial sets of frames, lenses, and currencies.
- Configured polymorphic relationships for prices to flexibly associate with both frames and lenses.




## Setup with Laravel Sail

Laravel Sail offers a straightforward interface to interact with Laravel's default Docker development environment. Before you begin, make sure Docker is installed on your system.

1. **Clone the Repository:** Clone the project repository to your local machine using your preferred method.
2. **Environment Configuration::** Run `cp .env.example .env`.
3. **Install PHP Dependencies:** Run `composer install`.
4. **Start Laravel Sail:** Execute `./vendor/bin/sail up -d` to build and start the Docker containers.
5. **Database Migration and Seeding:** `./vendor/bin/sail artisan migrate --seed`.


##  Design

The code design is structured around the Service Pattern, Request classes, Resource classes, and controllers, adhering closely to Laravel's conventions and best practices. The Service Pattern is employed to encapsulate business logic, ensuring controllers remain lean and focused solely on handling HTTP requests and responses. This separation of concerns enhances maintainability and scalability. Request classes are utilized for request validation, ensuring data integrity and security, while Resource classes format the responses consistently, providing a clear and predictable API structure. Controllers act as the entry point for HTTP requests, directing them to appropriate services. Error handling is systematic and standardized, aligning with HTTP status codes for clear client-side interpretation.

Laravel Sail is utilized to provide a consistent and easily configurable development environment. This design approach ensures a clean, testable, and efficient codebase that aligns with modern web application development standards.


# Database Design

The database architecture is optimized for efficient data retrieval and effective transaction handling.

## Tables

### Currencies

- **Purpose**: Stores the various currencies used for transactions.
- **Columns**:
  - `id` (Primary key): A unique identifier for each currency.
  - `code`: A three-letter ISO code representing the currency (e.g., USD, GBP, EUR).
  - `name`: The full name of the currency (e.g., United States Dollar, British Pound, Euro).

### Frames

- **Purpose**: Contains details about the different frames available for sale.
- **Columns**:
  - `id` (Primary key): Unique identifier for each frame.
  - `name`: The name of the frame.
  - `description`: A brief description of the frame.
  - `status`: Indicates whether the frame is active or inactive (e.g., active, inactive).
  - `stock`: Number of items currently available in stock.
- **Indexes**:
  - Primary search and transaction actions are indexed to enhance performance during queries and updates.

### Lenses

- **Purpose**: Stores information about the various types of lenses offered.
- **Columns**:
  - `id` (Primary key): Unique identifier for each lens.
  - `colour`: The color of the lens.
  - `description`: A detailed description of the lens.
  - `prescription_type`: Specifies the type of prescription the lens supports (e.g., single vision, varifocal).
  - `lens_type`: Specifies the type of lens (e.g., classic, blue_light, transition).
  - `stock`: Indicates how many units of the lens are currently in stock.
- **Indexes**:
  - Key columns are indexed to ensure efficient searches and data retrieval.

### Prices (Polymorphic Relationship Table)

- **Purpose**: Manages the prices of both frames and lenses in different currencies.
- **Columns**:
  - `id` (Primary key): Unique identifier for each pricing entry.
  - `priceable_type`: Distinguishes the product type (frame or lens).
  - `priceable_id`: Corresponds to the ID of the product in either the frames or lenses table.
  - `currency_id`: Links to the `currencies` table.
  - `price`: Specifies the price of the product in the linked currency.
- **Indexes**:
  - A composite index on `priceable_type`, `priceable_id`, and `currency_id` is used to optimize price lookups, crucial for converting prices and displaying product details efficiently.



# API Endpoints

Download Postman collection here: https://drive.google.com/file/d/1ssChXvnxJWjRyRItF_sNOO2-_qlEGFjw/view?usp=sharing

The APIs are structured into admin and user functionalities, supporting operations like viewing, adding frames and lenses, and creating custom glasses.

## Admin Endpoints

### Frames

- **GET /admin/frames**
  - **Purpose**: Retrieves a list of all frames currently available in the admin inventory.
  - **Response Keys**:
    - `id`: The unique identifier of the frame.
    - `name`: Name of the frame.
    - `description`: Description of the frame.
    - `status`: Current status (active or inactive).
    - `stock`: Number of units available.
    - `prices`: List of prices in different currencies.

- **POST /admin/frames**
  - **Purpose**: Allows the admin to add a new frame to the inventory.
  - **Request Keys**:
    - `name`: Name of the new frame.
    - `description`: Description of the frame.
    - `status`: Status setting for the frame (active or inactive).
    - `stock`: Initial stock count.
    - `prices`: Pricing details across various currencies.
  - **Response Keys**: Similar to GET, includes all details of the newly created frame.

### Lenses

- **GET /admin/lenses**
  - **Purpose**: Fetches a list of all lenses available in the admin section.
  - **Response Keys**:
    - `id`: Unique identifier of the lens.
    - `colour`: Color of the lens.
    - `description`: Description of the lens.
    - `prescription_type`: Type of prescription the lens supports.
    - `lens_type`: Type of the lens (e.g., classic, blue light, transition).
    - `stock`: Current stock level.
    - `prices`: Prices in different currencies.

- **POST /admin/lenses**
  - **Purpose**: Enables the admin to add new lenses to the inventory.
  - **Request Keys**:
    - `colour`: Color of the lens.
    - `description`: Detailed description.
    - `prescription_type`: Prescription type.
    - `lens_type`: Type of lens.
    - `stock`: Initial stock.
    - `prices`: List of prices per currency.
  - **Response Keys**: Includes detailed information on the newly added lens, similar to the GET request.

## User Endpoints

### Frames

- **GET /frames**
  - **Purpose**: Provides users with the list of all active frames available for purchase.
  - **Response Keys**:
    - `id`: Unique identifier of the frame.
    - `name`: Name of the frame.
    - `price`: Price based on the user's selected currency (determined by `X-Currency` header).

### Lenses

- **GET /lenses**
  - **Purpose**: Lists all available lenses to the users, regardless of the prescription type and lens type.
  - **Response Keys**:
    - `id`: Unique identifier of the lens.
    - `colour`: Color description.
    - `price`: Price adjusted to the user's selected currency.

### Custom Glasses

- **POST /custom-glasses**
  - **Purpose**: Allows users to create custom glasses by selecting a frame and a lens. The API also checks stock availability and updates stock counts upon successful creation.
  - **Request Keys**:
    - `frame_id`: ID of the selected frame.
    - `lens_id`: ID of the selected lens.
    - `X-Currency`: Currency code provided in the header to determine price.
  - **Response Keys**:
    - `message`: Confirmation of successful creation.
    - `frame_details`: Details of the selected frame including updated stock.
    - `lens_details`: Details of the selected lens including updated stock.
    - `total_price`: Total price of the custom glasses in the selected currency.
    - `currency`: Currency code used for pricing.

## General Notes

- All responses are in JSON format.
- Currency conversion is dynamic based on the `X-Currency` header for user-facing price information.
- Currency handling can be centralized to improve consistency and ease of maintenance across services.
- User registration and authentication are not included at this stage to maintain focus on core functionalities.






