DROP TABLE IF EXISTS moz_places;
CREATE TABLE moz_places(   
	id int NOT NULL AUTO_INCREMENT,
	video_id varchar(16),
	title varchar(255),
	last_visit_date int,
	PRIMARY KEY(id)
)DEFAULT CHARSET=utf8;
