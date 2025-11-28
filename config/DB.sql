CREATE TABLE user(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    hashed_password VARCHAR(250) NOT NULL,
    email VARCHAR(50) NOT NULL,
    full_name VARCHAR(50) NOT NULL,
    role_id INT,
    department_id INT NULL,
    created_by INT NULL,
    hire_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    FOREIGN KEY (created_by) REFERENCES user(id)
);

CREATE TABLE roles(
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name Enum('super admin','admin','employee') UNIQUE NOT NULL,
    descriptions TEXT NULL
)

CREATE TABLE department (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) UNIQUE NOT NULL,
    department_location VARCHAR(100) NULL,
    created_by INT NOT NULL,
    ceated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    FOREIGN KEY (created_by) REFERENCES user(id)
)

CREATE TABLE notifications (
    notificatio_id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    recepient_id INT NOT NULL,
    type  VARCHAR(50) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DAFAULT FALSE,
    FOREIGN KEY (recepient_id) REFERENCES user(id)   
)
CREATE TABLE task (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NULL,
    assigned_date DATE DEFAULT CURRENT_DATE,
    due_date  DATE NOT NULL,
    priority ENUM('Low','Medium','High') DEFAULT 'Medium',
    status ENUM('Pending','In Progress','Completed') DEFAULT 'Pending' NOT NULL,
    assigned_to INT NOT NULL,
    assigned_by INT NOT NULL,
    FOREIGN KEY (assigned_to) REFERENCES user(id),
    FOREIGN KEY (assigned_by) REFERENCES user(id)
)

CREATE TABLE task_update(
    update_id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('Pending','In Progress','Completed') DeFAULT 'Pending' NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES task(task_id),
    FOREIGN KEY (updated_by) REFERENCES user(id)
)