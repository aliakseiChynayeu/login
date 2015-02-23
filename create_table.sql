CREATE TABLE `users` ( 
`user_id` int(11) unsigned NOT NULL auto_increment, 
`user_login` varchar(30) NOT NULL, 
`user_password` varchar(32) NOT NULL, 
`user_hash` varchar(32) NOT NULL, 
`user_name` varchar(30) NOT NULL, 
`user_surname` varchar(30) NOT NULL,
`user_image` varchar(50) NOT NULL, 
PRIMARY KEY (`user_id`), 
INDEX (`user_login`(10))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; 