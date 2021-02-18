
INSERT IGNORE INTO users (id, name, first_name, last_name, email, password)
 VALUES
 (1, 'admin', 'admin-firstname', 'admin-lastname', 'admin@upei.ca', '1234qwer'),
 (2, 'st1', 'st1-firstname', 'st1-lastname', 'st1@upei.ca', '1234qwer'),
 (3, 'st2', 'st2-firstname', 'st2-lastname', 'st2@upei.ca', '1234qwer'),
 (4, 'ept1', 'ept1-firstname', 'ept1-lastname', 'ept1@upei.ca', '1234qwer');

INSERT IGNORE INTO roles (id, name, slug)
 VALUES
    (1, 'administrator', 'administrator'),
    (2, 'student', 'student'),
    (3, 'expert', 'expert');

INSERT IGNORE INTO users_roles (user_id, role_id)
 VALUES
 (1, 1),
 (2, 2),
 (3, 2),
 (4, 3);

INSERT IGNORE INTO permissions (id, name, slug)
 VALUES
    (1, 'takeQuizs', 'takeQuiz'),
    (2, 'createQuizs', 'createQuizs'),
    (3, 'createAccounts', 'createAccounts'),
    (4, 'editProfile', 'editProfile'),
    (5, 'importUsers', 'importUsers'),
    (6, 'exportStudents', 'exportStudents');


INSERT IGNORE INTO roles_permissions (role_id, permission_id)
 VALUES
    (1, 2),
    (1, 3),
    (1, 5),
    (2, 1),
    (3, 1),
    (3, 2);
