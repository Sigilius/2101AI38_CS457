# Role-Based Authentication System

A simple yet secure web-based portal for role-based authentication with MySQL integration. This system allows users to register, login, and access different features based on their assigned roles (admin, editor, or viewer).

## Features

- **User Authentication**: Secure login and registration with password hashing
- **Role-Based Access Control**: Three permission levels (admin, editor, viewer)
- **Student Database Management**: CRUD operations for student information
- **Admin Controls**: User role management and direct SQL query execution
- **Session Management**: Secure user sessions with appropriate access controls
- **Responsive Interface**: Simple, functional UI


## Technologies Used

- PHP
- MySQL
- HTML/CSS
- PDO for database connections
- Password hashing with bcrypt


## Installation

1. **Clone the repository**

```
git clone https://github.com/yourusername/role-based-auth.git
cd role-based-auth
```

2. **Database Setup**
    - Create a MySQL database named `auth_system`
    - Import the database structure using the SQL statements in `database_setup.sql` or run:

```sql
CREATE DATABASE IF NOT EXISTS auth_system;
USE auth_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'viewer') DEFAULT 'viewer'
);

CREATE TABLE stud_info (
    roll INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    branch VARCHAR(50),
    hometown VARCHAR(100)
);
```

3. **Configuration**
    - Update the database connection details in `config.php`

```php
$host = 'localhost';
$dbname = 'auth_system';
$username = 'your_mysql_username';
$password = 'your_mysql_password';
```

4. **Web Server Setup**
    - Place the files in your web server directory (e.g., htdocs for XAMPP)
    - Ensure your web server (Apache) and MySQL are running
5. **Create Admin User**
    - Register a new user through the registration page
    - Manually update this user to have admin role using MySQL:

```sql
UPDATE users SET role = 'admin' WHERE username = 'your_username';
```


## Usage

1. **Access the application**
    - Navigate to `http://localhost/role-based-auth/login.php` in your browser
2. **Login with your credentials**
    - Use the admin account you created during setup
3. **Navigate the system based on your role**
    - **Admin**: Full access to all features including user management
    - **Editor**: Can view, add, and edit student records
    - **Viewer**: Can only view student information

## File Structure

- `config.php` - Database connection configuration
- `register.php` - User registration form and processing
- `login.php` - Authentication page
- `dashboard.php` - Main interface after login
- `logout.php` - Session termination
- `manage_users.php` - Admin page for role management
- `add_student.php` - Form to add new student records
- `edit_student.php` - Form to modify existing student records
- `delete_student.php` - Endpoint to remove student records
- `run_query.php` - Admin-only SQL query execution


## Role Permissions

| Feature | Admin | Editor | Viewer |
| :-- | :-- | :-- | :-- |
| View student data | ✅ | ✅ | ✅ |
| Add students | ✅ | ✅ | ❌ |
| Edit students | ✅ | ✅ | ❌ |
| Delete students | ✅ | ❌ | ❌ |
| Run SQL queries | ✅ | ❌ | ❌ |
| Manage user roles | ✅ | ❌ | ❌ |

## Security Notes

- All passwords are securely hashed using PHP's `password_hash()` function
- Role-based access control prevents unauthorized operations
- Input validation is implemented to prevent SQL injection
- Session management is used to maintain authenticated states


## Important Notes

1. This is a barebones implementation intended for educational purposes
2. For production use, additional security measures should be implemented
3. The default implementation uses minimal styling for clarity

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request
