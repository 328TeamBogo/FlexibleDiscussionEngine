# FlexibleDiscussionEngine
Discussion Forum web design for SDEV 328 Project.

## Authors

- Eric Boyd - Developer
- Dale Kanikkeberg - Developer
- Ahmadreshad Yadgari - Developer

## Project Requirements

**1. Separates all database/business logic using the MVC pattern.**
- All HTML is in a views folder
- Validation is in a model directory
- Controller has functions called in index.php for routing

**2. Routes all URLs and leverages a templating language using the Fat-Free framework.**
- All routing is handled by index.php by calling function in the Controller

**3. Has a clearly defined database layer using PDO and prepared statements.**
- Not yet




**4. Data can be added and viewed.**
- Yes, discussions and posts can both be made by a signed-in user
- Signing up adds data to the database if valid

**5. Has a history of commits from both team members to a Git repository. Commits are clearly commented.**
- Commits from all team members are purposeful and descriptive

**6. Uses OOP, and utilizes multiple classes, including at least one inheritance relationship.**
- We have Post, User, and Admin classes
- Admin class inherits from User and adds the ability to archive discussions

**7. Contains full Docblocks for all PHP files and follows PEAR standards.**
- Not yet



**8. Has full validation on the server side through PHP.**
- All input fields have validation or sanitization

**9. All code is clean, clear, and well-commented. DRY (Don't Repeat Yourself) is practiced.**
- DRY is used as much as possible
- e.g. deletePost function uses a helper function to avoid repeating code between both Admins and normal Users

**10. Your submission is professional and shows adequate effort for a final project in a full-stack web development course.**
- In addition to fulfilling all requirements, we took on a challenging project involving dynamic routing
- This quarter we were able to take full advantage of Fat-Free's features to create a website we otherwise would not have been able to