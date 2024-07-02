# E-commerce Project Documentation

## Overview

The E-commerce project is a comprehensive platform for selling online. It includes features such as product management, user authentication, orders, reviews, comments, wishlists, and inventory management. This document provides an overview of the main components, database schema, API endpoints, and how to test the application.

## Key Components

1. **User Management**
    - Registration, Login, and Authentication
    - User Profiles
2. **Product Management**
    - CRUD operations for products
    - Product categories
    - Inventory management
3. **Order Management**
    - Create and manage orders
    - Order items management
    - Inventory updates on order creation
4. **Reviews and Comments**
    - Users can leave reviews and comments on products
    - Ensures users have purchased the product before reviewing
5. **Wishlist**
    - Users can add products to their wishlist
    - Retrieve wishlist items with product details
6. **Address Management**
    - Users can manage shipping addresses

## Database Schema

### Users Table
- id
- name
- email
- password
- created_at
- updated_at

### Products Table
- id
- name
- description
- price
- category_id
- brand
- size
- color
- gender
- created_at
- updated_at

### Categories Table
- id
- name
- description
- created_at
- updated_at

### Inventory Table
- id
- product_id
- quantity
- created_at
- updated_at

### Orders Table
- id
- user_id
- customer_name
- customer_email
- total_amount
- status
- created_at
- updated_at

### OrderItems Table
- id
- order_id
- product_id
- quantity
- unit_price
- created_at
- updated_at

### Reviews Table
- id
- user_id
- product_id
- rating
- review_text
- created_at
- updated_at

### Comments Table
- id
- review_id
- user_id
- comment_text
- created_at
- updated_at

### Addresses Table
- id
- user_id
- address_line_1
- address_line_2
- city
- state
- postal_code
- country
- created_at
- updated_at

### Wishlist Table
- id
- user_id
- product_id
- created_at
- updated_at

## API Endpoints

### Authentication
- **POST** `/api/auth/register` - Register a new user
- **POST** `/api/auth/login` - Login a user and get a token

### Products
- **GET** `/api/products` - List all products
- **GET** `/api/products/{id}` - Get a specific product
- **POST** `/api/products` - Create a new product
- **PUT** `/api/products/{id}` - Update a product
- **DELETE** `/api/products/{id}` - Delete a product

### Categories
- **GET** `/api/categories` - List all categories
- **POST** `/api/categories` - Create a new category
- **PUT** `/api/categories/{id}` - Update a category
- **DELETE** `/api/categories/{id}` - Delete a category

### Orders
- **POST** `/api/orders` - Create a new order
- **GET** `/api/orders/{id}` - Get order details
- **PUT** `/api/orders/{id}/restore` - Restore inventory for an order

### Reviews
- **POST** `/api/reviews` - Create a review for a product
- **PUT** `/api/reviews/{id}` - Update a review
- **DELETE** `/api/reviews/{id}` - Delete a review

### Comments
- **POST** `/api/comments` - Create a comment on a review
- **PUT** `/api/comments/{id}` - Update a comment
- **DELETE** `/api/comments/{id}` - Delete a comment

### Wishlist
- **GET** `/api/wishlist` - Get the wishlist of the authenticated user
- **POST** `/api/wishlist` - Add a product to the wishlist
- **DELETE** `/api/wishlist/{product_id}` - Remove a product from the wishlist

### Addresses
- **GET** `/api/addresses` - List all addresses for the authenticated user
- **POST** `/api/addresses` - Add a new address
- **PUT** `/api/addresses/{id}` - Update an address
- **DELETE** `/api/addresses/{id}` - Delete an address

## Testing with Postman

### Authentication
- Register a new user using the **POST** `/api/auth/register` endpoint.
- Login to get an authentication token using the **POST** `/api/auth/login` endpoint.

### Products
- Use the **GET** `/api/products` endpoint to list all products.
- Add a new product with the **POST** `/api/products` endpoint.
- Update a product with the **PUT** `/api/products/{id}` endpoint.
- Delete a product with the **DELETE** `/api/products/{id}` endpoint.

### Orders
- Create a new order using the **POST** `/api/orders` endpoint.
- Get order details with the **GET** `/api/orders/{id}` endpoint.
- Restore an order with the **PUT** `/api/orders/{id}/restore` endpoint.

### Reviews and Comments
- Create, update, and delete reviews and comments using the appropriate endpoints.

### Wishlist
- Add products to the wishlist using the **POST** `/api/wishlist` endpoint.
- Get the wishlist using the **GET** `/api/wishlist` endpoint.
- Remove products from the wishlist with the **DELETE** `/api/wishlist/{product_id}` endpoint.

### Addresses
- Add, update, and delete addresses using the appropriate endpoints.

## Conclusion

The E-commerce project provides a robust platform for managing an online store, complete with product management, user authentication, order processing, and user reviews. The API endpoints allow for comprehensive interaction with the system, and testing can be efficiently performed using Postman. This documentation serves as a guide to understanding the structure and functionality of the application.
