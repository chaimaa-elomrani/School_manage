CREATE TABLE person (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    type ENUM('student', 'teacher', 'parent', 'admin') NOT NULL
);

CREATE TABLE classes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    level VARCHAR(20) NOT NULL
);


CREATE TABLE subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(10) UNIQUE NOT NULL
);

CREATE TABLE rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    capacity INT NOT NULL
);



CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    person_id INT NOT NULL,
    student_number VARCHAR(20) UNIQUE NOT NULL,
    class_id INT,
    FOREIGN KEY (person_id) REFERENCES person(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE

);


CREATE TABLE teachers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    person_id INT NOT NULL,
    employee_number VARCHAR(20) UNIQUE NOT NULL,
    specialty VARCHAR(100),
    FOREIGN KEY (person_id) REFERENCES person(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE

);

CREATE TABLE parents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    person_id INT NOT NULL,
    student_id INT NOT NULL,
    FOREIGN KEY (person_id) REFERENCES person(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    class_id INT NOT NULL,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

CREATE TABLE schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    room_id INT NOT NULL,
    day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);


CREATE TABLE evaluations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    type ENUM('test', 'exam', 'homework', 'project') NOT NULL,
    date DATE NOT NULL,
    max_score DECIMAL(5,2) DEFAULT 20.00,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);


CREATE TABLE grades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evaluation_id INT NOT NULL,
    student_id INT NOT NULL,
    score DECIMAL(5,2),
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);


CREATE TABLE report_cards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    period VARCHAR(20) NOT NULL,
    average_score DECIMAL(5,2),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);


CREATE TABLE school_fees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    type ENUM('tuition', 'registration', 'transport', 'other') NOT NULL
);


CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    fee_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (fee_id) REFERENCES school_fees(id) ON DELETE CASCADE
);



CREATE TABLE salaries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    teacher_id INT NOT NULL,
    month VARCHAR(20) NOT NULL,
    year INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE,
    status ENUM('pending', 'paid') DEFAULT 'pending',
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE
);


CREATE TABLE notification_recipients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    notification_id INT NOT NULL,
    recipient_id INT NOT NULL,
    read_status BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (notification_id) REFERENCES notifications(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES person(id) ON DELETE CASCADE
);


