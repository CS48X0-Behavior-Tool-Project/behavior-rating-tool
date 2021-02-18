use laraveldb;

-- for testing - drop all the tables
drop table if exists user, user_session, role, ui_component, m_role_component;

-- user related
CREATE TABLE IF NOT EXISTS user (
    id MEDIUMINT NOT NULL AUTO_INCREMENT,
    username VARCHAR(20) NOT NULL,
    password VARCHAR(20),
    role_id MEDIUMINT,
    first_name VARCHAR(20),
    middle_name VARCHAR(20),
    last_name VARCHAR(20),
    email VARCHAR(255),
    info JSON,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS user_session (
    id MEDIUMINT NOT NULL AUTO_INCREMENT,
    user_id MEDIUMINT,
    start_time DATE,
    end_time DATE,
    state VARCHAR(20),
    info JSON,
    PRIMARY KEY (id)
);

-- role related
CREATE TABLE IF NOT EXISTS role (
    id MEDIUMINT NOT NULL AUTO_INCREMENT,
    role_name VARCHAR(20),
    info JSON,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS ui_component (
    id MEDIUMINT NOT NULL AUTO_INCREMENT,
    component_name VARCHAR(20),
    info JSON,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS m_role_component (
    id MEDIUMINT NOT NULL AUTO_INCREMENT,
    role_id MEDIUMINT,
    component_id MEDIUMINT,
    PRIMARY KEY (id)
);

INSERT INTO role (id, role_name)
    VALUES (1, 'administrator'), (2, 'student');

INSERT INTO ui_component (id, component_name)
    VALUES (1, 'quiz'), (2, 'account'), (3, 'profile'), (4, 'export'), (6, 'survey');

INSERT INTO m_role_component (role_id, component_id)
    VALUES (1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6),
            (2,1), (2,3), (2,6);

-- query Role and its coresponding priveledge
select r.role_name, c.component_name from role r
    inner join m_role_component m
        on r.id = m.role_id
    inner join ui_component c
        on c.id = m.component_id;

-- insert system default admin user
INSERT INTO user (username, password, role_id, first_name, last_name, email)
    VALUES (
        'admin',
        '123',
        1,
        'admin-first-name',
        'admin-last-name',
        'admin@upei.ca'
    );

-- testing user
INSERT INTO user (username, password, role_id, first_name, last_name, email, info)
    VALUES (
        'tami',
        '123',
        2,
        'teng',
        'liu',
        'tliu2@upei.ca',
        '{"information-1": "value1", "information-2": "value2"}'
    );


-- query Role and its coresponding priveledge
select r.role_name, c.component_name from role r
    inner join m_role_component m
        on r.id = m.role_id
    inner join ui_component c
        on c.id = m.component_id;

-- query user, Role and its coresponding priveledge
select u.username, r.role_name, c.component_name from role r
    inner join m_role_component m
        on r.id = m.role_id
    inner join ui_component c
        on c.id = m.component_id
    inner join user u
        on u.role_id = r.id;
