CREATE TABLE IF NOT EXISTS `users` (
    `id` INT PRIMARY KEY,
    `user` TEXT NOT NULL,
    `name` TEXT NOT NULL,
    `email` TEXT NOT NULL,
    `img` TEXT NOT NULL,
    `tkn` TEXT NOT NULL,
    `refresh_tkn` TEXT NOT NULL,
    `level` INT NOT NULL,
    `obs_host` TEXT,
    `obs_password` TEXT,
    `overlay` INT,
    `updated` DATETIME,
    `created` DATETIME,
    `status` BOOLEAN
);

CREATE TABLE IF NOT EXISTS `apikeys` (
    `aid` INT PRIMARY KEY,
    `uid` INT NOT NULL,
    `apikey` TEXT NOT NULL,
    `updated` DATETIME,
    `created` DATETIME,
    `status` BOOLEAN,
    FOREIGN KEY (uid)
        REFERENCES users(id)
            ON UPDATE NO ACTION
            ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `overlays` (
    `oid` INT PRIMARY KEY,
    `uid` INT NOT NULL,
    `reward` TEXT NOT NULL,
    `config` TEXT NOT NULL,
    `updated` DATETIME,
    `created` DATETIME,
    `status` BOOLEAN,
    FOREIGN KEY (uid)
        REFERENCES users(id)
            ON UPDATE NO ACTION
            ON DELETE CASCADE
);
