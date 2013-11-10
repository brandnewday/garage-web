CREATE TABLE CarMain
(
CarID int NOT NULL,
Make varchar(31) NOT NULL,
Model varchar(63) NOT NULL,
CarTypeID tinyint NOT NULL,
Year varchar(15) NOT NULL,
Colour varchar(15) NOT NULL,
Price numeric(7,2) NOT NULL,
Status varchar(31),
ShowroomSeq int,
HomepageSeq int,
ShowroomImgName varchar(127) NOT NULL,
NumDoors int NOT NULL,
Gearbox varchar(15) NOT NULL,
NumOwners tinyint,
Mileage int NOT NULL,
EngineCapacity varchar(15) NOT NULL,
SpecLayoutID int,
DescLayoutID int,
PRIMARY KEY(CarID)
);

CREATE TABLE CarDesc
(
CarID int,
ParNum int,
DescText text,
TextStyle varchar(15),
PRIMARY KEY(CarID, ParNum)
);

CREATE TABLE CarImg
(
CarID int,
ImgID int,
FileName varchar(127),
LayoutID int,
LargeImgFileName varchar(127),
PRIMARY KEY(CarID, ImgID)
);

CREATE TABLE DetailLayout
(
CarID int,
ObjectID int,
ObjectType char(8),
LeftPx int NOT NULL,
TopPx int NOT NULL,
ZIdx int NOT NULL,
Width int,
Height int,
PRIMARY KEY(CarID, ObjectID)
);

CREATE TABLE Stats
(
CarID int,
Hit int,
LastIP char(15),
LastAccess int,
PRIMARY KEY (CarID)
);

CREATE TABLE AdminUser
(
UserName varchar(15),
Password varchar(31),
FirstName varchar(15),
LastName varchar(31),
PRIMARY KEY(username)
);

CREATE TABLE CarType
(
CarTypeID tinyint,
CarTypeName varchar(31) UNIQUE NOT NULL,
PRIMARY KEY(CarTypeID)
);


INSERT INTO CarType VALUES (1,'4 x 4');
INSERT INTO CarType VALUES (2,'Prestige');
INSERT INTO CarType VALUES (3,'Sports');
INSERT INTO CarType VALUES (4,'Hatchback');
INSERT INTO CarType VALUES (5,'Saloon');
INSERT INTO CarType VALUES (6,'MPV');
INSERT INTO CarType VALUES (7,'Family');
INSERT INTO CarType VALUES (8,'Classic');
INSERT INTO CarType VALUES (9,'Estate');
INSERT INTO CarType VALUES (10,'Convertible');
INSERT INTO CarType VALUES (11,'Motorcycle');
INSERT INTO CarType VALUES (12,'Van');
INSERT INTO CarType VALUES (13,'Truck');

INSERT INTO AdminUser VALUES ('admin','mypass','Ken','Goh');
