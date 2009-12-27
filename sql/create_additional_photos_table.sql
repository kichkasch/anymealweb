CREATE TABLE `anymeal`.`photos` (
`id` INT( 10 ) NOT NULL ,
`recipeid` INT( 10 ) NOT NULL ,
`comment` VARCHAR( 100 ) NOT NULL ,
`url` VARCHAR( 100 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;