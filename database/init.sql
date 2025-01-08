CREATE DATABASE IF NOT EXISTS PHP_PetStore_Test;

CREATE DATABASE IF NOT EXISTS PHP_PetStore;

GRANT ALL PRIVILEGES ON PHP_PetStore.* TO 'petstore_user'@'%';
GRANT ALL PRIVILEGES ON PHP_PetStore_Test.* TO 'petstore_user'@'%';

FLUSH PRIVILEGES;
