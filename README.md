Khabo Koi is a backend-only system designed to provide **organic restaurant reviews** in Bangladesh. It addresses the lack of unbiased reviews by ensuring all feedback and ratings are genuine and not influenced by paid promotions. Users can authenticate and interact with the platform via APIs, managing restaurants, branches, reviews, and ratings without any frontend interface.

## Feature Description

- **User Authentication**: Supports secure user registration and login with JWT-based authentication and Google OAuth.
- **Restaurant Management**: Restaurant owners can create, update, or delete restaurant profiles and assign owners to restaurants.
- **Branch Management**: Add, update, or remove specific restaurant branches with location details (address, area, etc.).
- **Review System**: Users can post new reviews for restaurant branches, as well as update or delete their existing reviews.
- **Rating System**: Users submit ratings for branches, which are aggregated into an average rating for each branch.
- **Cuisine Management**: Manage categories of cuisines; users can review and rate specific cuisines or categories.
- **Feature Listings**: Restaurant branches can highlight special features (e.g. *candlelight dinner* availability) and mark them as available or unavailable.
- **Data Analysis**: The system provides daily, monthly, and yearly summary reports on total reviews and average ratings.

## Navigation (API Usage)

Since Khabo Koi is a backend-only project, all interactions are done through RESTful API calls (for example, using Postman or similar tools). After authentication, users use the API endpoints to retrieve data or perform actions. For instance, a user would log in to obtain a JWT token, then include this token in subsequent requests to create restaurants, add branches, post reviews, etc. Each operation corresponds to a specific API endpoint as documented in the system.

## Tools and Technology

- **Framework**: Laravel 10 (PHP framework for building the API backend).
- **Programming Language**: PHP 8.2.
- **Database**: MySQL (relational database for storing users, restaurants, reviews, etc.).
- **Authentication**: *tymon/jwt-auth* library for JWT token authentication, and Laravel Socialite for Google OAuth login.
- **API Testing**: Postman (for testing endpoints and simulating client requests).
- **Date/Time Management**: Carbon library (for handling and formatting dates, e.g. generating time-based reports).

## API Design

The API follows RESTful principles, with endpoints organized by resource and function. Major API groupings include:

- **Authentication APIs**: Endpoints for user registration, login (issuing JWT tokens), retrieving the authenticated user profile (`/api/auth/me`), Google OAuth redirection, and logout (invalidating tokens).
- **Restaurant & Branch APIs**: Endpoints to create new restaurants (`/api/restaurant/create`), assign an owner to a restaurant, manage branch details (creating branches, updating branch info, deleting a branch), and listing branches.
- **Review APIs**: Endpoints for users to post a review to a restaurant branch, edit/update their review, or delete a review.
- **Rating APIs**: Endpoints to submit a rating for a branch or cuisine, update a rating, and fetch aggregated rating information.
- **Feature APIs**: Endpoints to add special features to a branch (either selecting from predefined features or creating new ones), update feature availability or details, delete a feature from a branch, and list all branches that have a certain feature.
- **Cuisine APIs**: Endpoints to create and manage cuisine categories, and to retrieve or rate those cuisines.
- **Data Analysis APIs**: Endpoints that provide aggregated counts and statistics, such as the number of reviews or average ratings over daily, monthly, or yearly intervals.

Each API endpoint returns JSON responses, and many require an Authorization header with the user's JWT token to ensure secure access.

## Use Case Scenarios

1. **User Registration and Login** – A new user signs up with a unique username, email, and password. After registering, the user logs in to receive a JWT token for authenticated requests.
2. **Browsing Restaurants** – The user searches for a restaurant through the API and retrieves its details, including all branches, reviews, and average ratings for those branches.
3. **Restaurant Owner Adds Branch** – A restaurant owner (who has owner privileges on a restaurant) uses the API to add a new branch for their restaurant, providing details like location and contact info. They can also update branch information or remove a branch if needed.
4. **Submitting a Review and Rating** – After visiting a restaurant branch, a user posts a review for that branch through the API, along with a numeric rating. If the user notices a mistake, they can update their review or rating, or delete it entirely.
5. **Aggregating Analytics** – The system automatically recalculates the branch’s average rating whenever new ratings are added or updated. An admin or user can request daily, monthly, or yearly analytics (such as the total number of new reviews or average ratings over time) through the data analysis endpoints.

## Challenges & Solutions

- **JWT and OAuth Integration**: Laravel’s default authentication is session-based and intended for web apps, which was not suitable for a pure API service. This was solved by integrating the *tymon/jwt-auth* package to handle JWT token creation and validation ([Best Way to Implement JWT Authentication in Laravel? - Laracasts](https://laracasts.com/discuss/channels/laravel/best-way-to-implement-jwt-authentication-in-laravel#:~:text=Laracasts%20laracasts,auth)). Additionally, Laravel Socialite was configured for Google OAuth to allow users to log in via Google accounts. These integrations required adjusting the auth configuration and ensuring that protected routes validate the JWT token on each request.
- **JSON Response Handling**: Transitioning from Laravel’s Blade templating (used for HTML views) to a JSON-only API required restructuring how data is returned from controllers. The solution was to use Laravel API Resource classes to consistently transform models into JSON responses. Controllers were refactored to return standardized JSON output (including success status, data payload, and error messages if any) instead of web pages, making the API responses more predictable and easier to consume.

## Future Improvements

- **User Wishlist**: Implement an endpoint for users to save favorite restaurants or cuisines to a personal wishlist. This would allow the system to later provide quick access to saved items or send notifications for those restaurants.
- **Search History & Personalization**: Keep track of users’ recent search queries or viewed restaurants. By analyzing this history, the system could offer personalized recommendations or shortcuts (for example, suggesting new restaurants similar to ones a user frequently views).
- **AI-Based Suggestions**: Leverage machine learning to analyze user behavior and preferences (favorite cuisines, highly rated restaurants, etc.) and provide AI-driven food and restaurant suggestions. This could be done via a recommendation engine that learns from reviews and ratings to highlight restaurants a user might like.

## Conclusion

Khabo Koi is an API-driven platform focused on transparent and organic restaurant reviews in Bangladesh. By leveraging Laravel’s robust backend capabilities along with JWT authentication for stateless security, the system ensures that user interactions are secure and data integrity is maintained. The project’s design as a backend-only service underscores its flexibility—clients could range from mobile apps to web frontends, all consuming the same API. Future enhancements like AI-based recommendations and search history tracking could further enrich the user experience by making the system more personalized. Overall, developing Khabo Koi provided valuable experience in building a secure, scalable RESTful API and handling authentication and authorization in a Laravel environment.
