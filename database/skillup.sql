
-- ============================================
-- Users table
-- ============================================
CREATE TABLE IF NOT EXISTS credentials (
    Username VARCHAR(100) NOT NULL PRIMARY KEY,
    Email VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Mobile_Number VARCHAR(20),
    ProfilePicture LONGBLOB,
    ImageName VARCHAR(255),
    ImageType VARCHAR(100),
    ImageSize INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Business accounts table
-- ============================================
CREATE TABLE IF NOT EXISTS business (
    BusinessName VARCHAR(100) NOT NULL PRIMARY KEY,
    Email VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Mobile_Number VARCHAR(20),
    ProfilePicture LONGBLOB,
    imageName VARCHAR(255),
    imageType VARCHAR(100),
    imageSize INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Post images table (must be created before posts)
-- ============================================
CREATE TABLE IF NOT EXISTS images (
    ImageId INT AUTO_INCREMENT PRIMARY KEY,
    ImageContent LONGBLOB NOT NULL,
    ImageType VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Social posts table
-- ============================================
CREATE TABLE IF NOT EXISTS posts (
    PostId INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(100) NOT NULL,
    Content TEXT NOT NULL,
    ImageId INT,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Username) REFERENCES credentials(Username) ON DELETE CASCADE,
    FOREIGN KEY (ImageId) REFERENCES images(ImageId) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Courses table
-- ============================================
CREATE TABLE IF NOT EXISTS courses (
    CourseId INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(100) NOT NULL,
    courseName VARCHAR(255) NOT NULL,
    courseHeading VARCHAR(255),
    coursePrice DECIMAL(10,2),
    courseOfferPrice DECIMAL(10,2),
    courseRating DECIMAL(3,1),
    courseReviews INT DEFAULT 0,
    courseImg TEXT,
    FOREIGN KEY (Username) REFERENCES credentials(Username) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Job postings table
-- ============================================
CREATE TABLE IF NOT EXISTS jobs (
    JobId INT AUTO_INCREMENT PRIMARY KEY,
    jobName VARCHAR(255) NOT NULL,
    jobDescription TEXT,
    jobImage LONGBLOB,
    imageName VARCHAR(255),
    imageType VARCHAR(100),
    imageSize INT,
    BusinessName VARCHAR(100) NOT NULL,
    FOREIGN KEY (BusinessName) REFERENCES business(BusinessName) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
