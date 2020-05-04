ALTER TABLE offers
    ADD collectif text,
    ADD referent_name text,
    ADD referent_phonenumber varchar(100),
    ADD panier boolean,
    ADD related_products text,
    ADD nb_children int;

CREATE TABLE children (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    offer_id bigint(20),
    child_name text,
    child_age text,
    child_weight text,
    layer_size text,
    milk boolean,
    milk_age text,
    milk_brand text,
    primary key (`id`)
);

-- Pour trouver les utilisateurs catégorie "couches" :
SELECT id
FROM users 
WHERE category = "couches";

-- Puis pour chaque id :
UPDATE offers
SET category = "couches"
WHERE user_id = [user_id];

-- Puis on change la catégorie des utilisateurs "couches" en utilisateurs "coordinateur"
UPDATE users
SET category = 'coordinateur'
WHERE category = 'couches';
