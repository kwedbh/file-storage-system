# file storage  Sharing System

A simple and secure file-sharing application inspired by file storage  Built with a focus on simplicity, speed, and temporary file sharing.

## ✨ Features

-   **Drag-and-Drop Uploads**: A clean, intuitive user interface for easy file selection.
-   **Real-time Progress Bar**: Users can see the upload progress and percentage in real-time.
-   **Temporary File Storage**: Uploaded files are automatically deleted after a set period (7 days).
-   **Secure Download Links**: Unique, hard-to-guess URLs for each uploaded file to prevent unauthorized access.
-   **Recipient-friendly Links**: Download links provided to recipients lead directly to a download page, with no extra options or clutter.
-   **Responsive Design**: Built with Bootstrap to work seamlessly on desktops, tablets, and mobile phones.

---

## 🛠️ Technology Stack

-   **Frontend**: HTML5, CSS3 (with **Bootstrap 5**), JavaScript (**jQuery**)
-   **Backend**: **PHP**
-   **Database**: **MySQL**

---

## 🚀 Getting Started

### Prerequisites

-   A web server with PHP (e.g., Apache, Nginx)
-   MySQL database
-   Composer (optional, but recommended for dependency management)

### Installation

1.  **Clone the repository**:
    ```bash
    git clone [https://github.com/kwedbh/file-storage-system.git](https://github.com/kwedbh/file-storage-system.git)
    cd file storage clone
    ```

2.  **Database Setup**:
    Create a new MySQL database named `file storage db`. Run the following SQL query to create the `files` table:

    ```sql
    CREATE TABLE files (
        id INT AUTO_INCREMENT PRIMARY KEY,
        original_name VARCHAR(255) NOT NULL,
        unique_name VARCHAR(255) NOT NULL,
        file_size BIGINT NOT NULL,
        upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expires_at TIMESTAMP
    );
    ```

3.  **Configure Database Connection**:
    Open the following files and update the database credentials to match your own:
    -   `upload.php`
    -   `file.php`
    -   `download.php`

4.  **Set up the `downloads` folder**:
    Create a new folder named `downloads` in the root directory of your project. This folder is where all uploaded files will be stored.
    ```bash
    mkdir downloads
    ```
    You **must** set the correct permissions for your web server to write to this folder. On Linux/macOS, use:
    ```bash
    chmod 775 downloads
    ```
    or, for better security, give ownership to your web server user (e.g., `www-data` on Ubuntu):
    ```bash
    sudo chown -R www-data:www-data downloads
    ```

5.  **Run the Application**:
    Navigate to the application's URL in your web browser, and you should see the homepage.

---

## 🗑️ Automatic File Deletion

Files are automatically marked for deletion in the database after 7 days. To remove the files from the disk, you need to set up a cron job to run a cleanup script regularly.

1.  Create a new PHP file, for example, `cleanup.php`, in your root directory.

2.  Add the following code to `cleanup.php`:
    ```php
    <?php
    // Database credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "file storage db";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT unique_name FROM files WHERE expires_at < NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $filePath = 'downloads/' . $row['unique_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    $delete_sql = "DELETE FROM files WHERE expires_at < NOW()";
    $conn->query($delete_sql);
    $conn->close();

    echo "Cleanup complete.";
    ?> 
    ```

3.  Set up a cron job to run this script daily. Here is an example cron command:
    ```bash
    0 2 * * * /usr/bin/php /path/to/your/app/cleanup.php > /dev/null 2>&1
    ```
    This command runs the `cleanup.php` script at 2:00 AM every day.

---

## 🤝 Contribution

Contributions are welcome! If you find any bugs or have suggestions for improvements, please open an issue or submit a pull request.

---

## 📜 License

This project is open-source and available under the **MIT License**.
