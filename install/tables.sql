
CREATE TABLE users (
    id BIGINT NOT NULL AUTO_INCREMENT,
    date_create TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modify TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    date_lastactivity TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    login VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    status VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phonenumber VARCHAR(100),
    name VARCHAR(100) NOT NULL,
    firstname VARCHAR(100),
    gender VARCHAR(100),
    address TEXT,
    arrondissement TEXT,
    geolocalisation INT,
    cgu_ack TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    rgpd_ack TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(100) DEFAULT NULL,
    admin_comments TEXT,
    create_token VARCHAR(100),
    notification VARCHAR(100),
    accept_mailing VARCHAR(100),
    PRIMARY KEY (`id`)
);

CREATE TABLE offers (
    id BIGINT NOT NULL AUTO_INCREMENT,
    date_create TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modify TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    date_lastactivity TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id BIGINT,
    category VARCHAR(100),
    status VARCHAR(100),
    date_start TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_end TIMESTAMP,
    date_type TEXT,
    title TEXT,
    description TEXT,
    address TEXT,
    arrondissement TEXT,
    geolocalisation INT,
    picture LONGBLOB,
    admin_comments TEXT,
    PRIMARY KEY (`id`)
);

--CREATE TABLE asks (
--    id BIGINT NOT NULL AUTO_INCREMENT,
--    date_create TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--    asker_id BIGINT,
--    benevole_id BIGINT,
--    offer_id BIGINT,
--    message_id BIGINT,
--    status VARCHAR(100),
--    PRIMARY KEY (`id`)
--);

CREATE TABLE messages (
    id BIGINT NOT NULL AUTO_INCREMENT,
    date_create TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    offer_id BIGINT NOT NULL,
    from_id BIGINT NOT NULL,
    to_id BIGINT NOT NULL,
    message TEXT,
    PRIMARY KEY (`id`)
);

