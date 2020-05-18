ALTER TABLE offers ADD INDEX offers_user_id (user_id);
ALTER TABLE offers ADD INDEX offers_status (status);
ALTER TABLE offers ADD INDEX offers_type (offer_type);
ALTER TABLE offers ADD INDEX offers_arrondissement (arrondissement(5));
ALTER TABLE users ADD INDEX users_status (status);
