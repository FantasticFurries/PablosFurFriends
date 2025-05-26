create database pff;
use pff;
create table users (
	userID int primary key auto_increment not null,
    fname varchar(30) not null,
    lname varchar(30) not null,
    email varchar(50) not null,
    userPW varchar(255) not null
);
create table admin_users (
	adminID int primary key auto_increment not null,
    username varchar(30) not null,
    password varchar(255) not null
);
insert into admin_users (username, password)
values
	('admin','password'),
	('staff','password');
select * from admin_users;
truncate admin_users;
update admin_users set username = 'gian' where adminID = 1;
drop table admin_users;

create table staffs (
	staffID int primary key auto_increment not null,
    fname varchar(30) not null,
    lname varchar(30) not null,
    age int not null,
    bday date not null,
    address varchar(50),
    employment_date date not null,
    email varchar(30),
    status varchar(10),
    type varchar(30),
    adminID int,
    foreign key (adminID) references admin_users(adminID),
    staff_pic varchar(255)
);
ALTER TABLE staffs ADD COLUMN staff_pic VARCHAR(255) DEFAULT NULL;
UPDATE staffs SET staff_pic = 'staff1.jpg' WHERE staffID = 1;
UPDATE staffs SET staff_pic = 'staff2.jpg' WHERE staffID = 2;
UPDATE staffs SET staff_pic = 'staff3.jpg' WHERE staffID = 3;
UPDATE staffs SET staff_pic = 'staff4.jpg' WHERE staffID = 4;
UPDATE staffs SET staff_pic = 'staff5.jpg' WHERE staffID = 5;
UPDATE staffs SET staff_pic = 'staff6.jpg' WHERE staffID = 6;
select * from staffs;
truncate staffs;
drop table staffs;
ALTER TABLE staffs AUTO_INCREMENT = 9;
insert into staffs (fname, lname, age, bday, address, employment_date, email, status, type, adminID, staff_pic)
values
	('Gian Brent','Maligaya',19,'2006-01-01','Taguig City','2025-01-01','gmaligaya.8912@umak.edu.ph','ACTIVE','CO-OWNER',1,'staff1.jpg'),
	('Dave','Espinol',20,'2004-01-01','Taguig City','2025-01-01','despinol.1234@umak.edu.ph','ACTIVE','CASHIER',2,'staff2.jpg'),
	('Shareen','Bola',19,'2005-01-01','Makati City','2025-01-01','sbola.1234@umak.edu.ph','ACTIVE','SUPERVISOR',null,'staff3.jpg'),
	('John David','Bomediano',18,'2006-03-08','Taguig City','2025-01-01','jbomediano.1234@umak.edu.ph','ACTIVE','GUARD',2,'staff4.jpg'),
	('Anjelo','Arnaez',19,'2006-01-01','Taguig City','2025-01-01','arnaez.k12345678@umak.edu.ph','ACTIVE','GROOMER',null,'staff5.jpg'),
	('Simone Philippe','Nacionales',19,'2006-01-01','Pasig City','2025-01-01','snacionales.1234@umak.edu.ph','ACTIVE','NURSE',null,'staff6.jpg');
insert into staffs (fname, lname, age, bday, address, employment_date, email, status, type, adminID)
values
	('Sample','Employee',99,'2001-01-01','Taguig City','2025-01-01','semployee.1234@umak.edu.ph','ACTIVE','JANITOR',null);

create table reviews (
	reviewID int primary key auto_increment not null,
    fname varchar(30) not null,
    lname varchar(30) not null,
    feedback text(100),
    date date
);
insert into reviews (fname, lname, feedback, date)
values
	('Gian Brent','Maligaya','The service is very good, yes, yes.','2025/05/23'),
	('Era Marie','Gannaban','Where is your DATABASE??','2025/05/23'),
	('Shareen','Bola','balls.','2025/05/23'),
	('Long','Hotdog','hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog hotdog','2025/05/23');
select * from reviews;
truncate table reviews;

create table categories (
	categoryID INT AUTO_INCREMENT PRIMARY KEY not null,
	category_title VARCHAR(100) NOT NULL
);
INSERT INTO categories (category_title)
VALUES  
	('Pet Foods'),
	('Pet Treats'),
	('Grooming Supplies'),
	('Medicine & Supplements'),
	('Others');
select * from categories;

CREATE TABLE appointments (
    appID INT AUTO_INCREMENT PRIMARY KEY not null,
    service VARCHAR(50) not null,
    name VARCHAR(100),
    phone VARCHAR(15),
    email VARCHAR(100),
    appointment_date DATE,
    status VARCHAR(50) DEFAULT 'Pending',
	petID INT,
    FOREIGN KEY (petID) REFERENCES pets(petID),
    userID INT,
    FOREIGN KEY (userID) REFERENCES users(userID)
);
create table reviews ( 
	reviewID int primary key auto_increment not null,
	fname varchar(30) not null,
	lname varchar(30) not null,
	feedback text not null,
	date date
);
create Table pets (
	petID int primary key auto_increment not null,
	petName varchar(30) not null,
	ownerID INT NOT NULL,
	FOREIGN KEY (ownerID) REFERENCES users(userID)
);
select * from pets;
select * from reviews;
truncate reviews;
truncate pets;
truncate appointments;

create table products (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50),
	description TEXT,
	category VARCHAR(100) NOT NULL,
	price DECIMAL(10,2),
	image_url VARCHAR(200)
);
INSERT INTO products (name, description, category, price, image_url) VALUES
	('SLEEKY Chewy Snack(Beef) 50g', 'Has great taste and nutritionally complete. Suitable for all breeds and all life stages.', 'Pet Treats', 80.00, '682fe3c7a786f.png'),
	('SLEEKY Chewy Snack(Lamb) 50g', 'Has great taste and nutritionally complete. Suitable for all breeds and all life stages.', 'Pet Treats', 80.00, '682fe3f454586.png'),
	('SLEEKY Chewy Snack(Chicken) 50g', 'Has great taste and nutritionally complete. Suitable for all breeds and all life stages.', 'Pet Treats', 80.00, '682fe41b18624.png'),
	('SLEEKY Chewy Snack(Bacon) 50g', 'Has great taste and nutritionally complete. Suitable for all breeds and all life stages.', 'Pet Treats', 80.00, '682fe43e279c4.png'),
	('POTOPET Pet Treats (Salmon) 15g x 10PCS', 'High in moisture - to effectively replenish lost fluid in your cat''s body, helps with urinary tract infection and cats who consume too little water.', 'Pet Treats', 100.00, '682fe80f4964d.png'),
	('POTOPET Pet Treats (Chicken) 15g x 10PCS', 'High in moisture - to effectively replenish lost fluid in your cat''s body, helps with urinary tract infection and cats who consume too little water.', 'Pet Treats', 100.00, '682fe83b29537.png'),
	('POTOPET Pet Treats(Tuna)  15g', 'High in moisture - to effectively replenish lost fluid in your cat''s body, helps with urinary tract infection and cats who consume too little water.', 'Pet Treats', 100.00, '682fe8604a0ee.png'),
	('Zert DentaCare(Cheese) 148g', 'Created to clean your dog''s teeth. Prevents plaque build up. Freshens breath.', 'Pet Treats', 150.00, '682fe8cf0afe7.png'),
	('Bioline Catnip Biscuits (Salmon) 80g', 'Contains natural catnip leaves, has excellent palatability. It can help the cats increase exercises to control weights and to improve the health.', 'Pet Treats', 100.00, '682fe8e8701da.png'),
	('Entree (Ham & Cheese) 70g', 'Packed with premium taste, high meat content, and luscious flavoring to make every bite special.', 'Pet Treats', 90.00, '682fe8fea44af.png'),
	('Pedigree Dentastix 7 sticks 50g', 'A healthy dog treat with benefits low in fat, no added sugar, no artificial colors and flavors.', 'Pet Treats', 120.00, '682fe9106050e.png'),
	('CHAMPION Dog treats (Chicken) 100g', '100% Natural and human grade food. Healthy safe dog treats & best training reward treats.', 'Pet Treats', 79.00, '682fe923bb2eb.png'),
	('Champion Dog Treats (Beef) 100g', '100% Natural and human grade food. Healthy safe dog treats & best training reward treats.', 'Pet Treats', 79.00, '682fe93ab781d.png'),
	('Champion Dog Treats (Milk)  100g', '100% Natural and human grade food. Healthy safe dog treats & best training reward treats.', 'Pet Treats', 79.00, '682fe94adefe7.png'),
	('WUJIBRAND Jerky Stick (Carrot) 70g', 'High in protein helps develop muscles and repair tissues. Vitamin D3 helps achieve strong bones and teeth.', 'Pet Treats', 95.00, '682fe95cee6ac.png'),
	('WUJIBRAND Jerky Stick (Fish) 70g', 'High in protein helps develop muscles and repair tissues. Vitamin D3 helps achieve strong bones and teeth.', 'Pet Treats', 95.00, '682fe97001862.png'),
	('AOZI Pure natural organic food 430g', 'High-quality coarse food grain, providing enough protein and energy. Low-allergic and high-quality beef, for dogs with allergic constitution.', 'Pet Foods', 230.00, '682fe9f6ca58f.png'),
	('Special DOG TRIPPA di AGNELLO 400g', 'Made in Italy. Steam cooked. 100% single-protein. Without preservatives & added sugar. Ideal for adult dog of all breeds & sizes.', 'Pet Foods', 80.00, '682fea2a342d4.png'),
	('Pedigree Dog Food 400g', 'Specially formulated to provide essential nutrients such as DHA, fiber, and calcium, all crucial for optimal brain development in your growing puppy.', 'Pet Foods', 120.00, '682fea40ebee7.png'),
	('Whiskas Cat Food 85g', 'Made with 100% high quality ingredients from real fish and chicken. Contain DHA, Omega 3 to support healthy brain and vision development.', 'Pet Foods', 40.00, '682fea4e6556c.png'),
	('JerHigh Chicken Beef 120g', 'Premium dog food suitable for all dog breeds and chock full of protein and nutritional value with added omega 3 and zinc to promote healthy skin and fur.', 'Pet Foods', 45.00, '682fea94d1c9d.png'),
	('JerHigh Roasted Duck 120g', 'Chock full of nutritional value from vitamin E to promote silky fur and skin. It is made from real chicken and liver, formed into delectable meaty chunks and topped with our delicious gravy.', 'Pet Foods', 50.00, '682feab3bef72.png'),
	('JerHigh Chicken Jerky 50g', 'Made from real chicken meat. Loaded with essential vitamins, boost muscle growth & healthy bones.', 'Pet Foods', 100.00, '682feacf2b862.png'),
	('JerHigh Liver 70g', 'A premium snack filled with nutrients and vitamins to make your dog healthy and strong.', 'Pet Foods', 100.00, '682feadf58908.png'),
	('Jinny Catnip 35g', 'Made from real chicken meat. Prepared with quality standards, and a hygienic. Loaded with taurine, vitamin E & calcium', 'Pet Foods', 50.00, '682feaefd7f90.png'),
	('COSI Pet''s Milk 1L', 'A formulated and highly delicious milk which is an ideal supplement to dry pet food for your dogs and cats.', 'Pet Foods', 200.00, '682fec03abf16.png'),
	('Double Sided Comb', 'Brush has soft boar and nylon bristles on one side to gently work out mats and tangles.', 'Grooming Supplies', 120.00, '682fecc2e95ea.png'),
	('BIOLINE Kitten Shampoo 200ml', 'With deep nourishing formula and adds VA, VH, Pronalen Aesculus and Arnica Ext., special for kittens'' sensitive fur, and make the fur healthy and shiny.', 'Grooming Supplies', 250.00, '682fecd731670.png'),
	('Saint Gertie Premium Cat Shampoo 250ml', 'Has properties that balances the Ph levels thus preventing allergic reactions. Guarantees to make your cats fresh smelling for a period of 14 days.', 'Grooming Supplies', 185.00, '682fece6b92f3.png'),
	('BIOLINE Cat Shampoo 250ml', 'Created specially for long hair cats, makes combing easier and prevents tangling.', 'Grooming Supplies', 175.00, '682fecf97c117.png'),
	('Fur Magic Dog Shampoo 1000ml', 'Fur magic''s Organic Dog Shampoo is made with wholesome, natural ingredients that are good for your dog and the environment.', 'Grooming Supplies', 650.00, '682fed0d3ba9e.png'),
	('JOJOBA Ear Cleansing Liquid 120ml', 'Effective, safe to use and no skin irritation. Safe for dogs and cats of all ages.', 'Grooming Supplies', 290.00, '682fed21e0088.png'),
	('MADRE DE CACAO Shampoo w/ Conditioner 500ml', 'Helps to regrow fur loss, helps get rid of ticks and fleas, treats yeast infection, treats smelly coats.', 'Grooming Supplies', 350.00, '682fed34888c4.png'),
	('Mutant Lab Pet Shampoo 250ml', 'Our Medicated pet shampoo is pH balanced, paraben free and is highly recommended for moisturizing dry skin & providing itch relief.', 'Grooming Supplies', 210.00, '682fed430c01d.png'),
	('Broncure 60ml', 'Broncure is a natural antibiotic from fermented herbs wit oregano and sambong. Aimed to combat respiratory ailments in companion animals.', 'Medicine & Supplements', 220.00, '682fedbdd07cb.png'),
	('D-Glucose Monohydrate 100g', 'Fast absorbed from the digestive tract to provide instant energy.', 'Medicine & Supplements', 90.00, '682fedcccdddc.png'),
	('LC-VIT SYRUP 120ml', 'Prevents and treats vitamin deficiencies, especially during periods of stress.', 'Medicine & Supplements', 145.00, '682fedda61620.png'),
	('OTIDERM 15ml', 'Ear Cleansing Solution is specifically formulated to deodorize and gently clean, while it acidifies the pet''s ear canal and self-dries.', 'Medicine & Supplements', 350.00, '682fedebef2a4.png'),
	('OPTHASURE Eye Care 40ml', 'It can remove tear stains tear marks from the eyes of dogs and cats and other pets. Restores the luster of the hair around the eye and relieves dryness.', 'Medicine & Supplements', 250.00, '682fedfd20f38.png'),
	('Pet Bowl', 'Thick Stainless Steel Non-slip Feeding Bowl Pet Water bowl Dog Food Bowl Cat Food Bowl.', 'Others', 70.00, '682ff34d6307e.png'),
	('Tennis Ball toy', 'Perfect toy for tough chewing pets. Safe and durable for hours of fun.', 'Others', 50.00, '682ff3612fd4c.png'),
	('DONO Carbon Fiber Training Pad 50 pcs', 'With carbon technology for odor elimination to ensure your home stays fresh Highly absorbent ensure less mess, locks in moisture to stop leakage.', 'Others', 450.00, '682ff373a0dd8.png'),
	('Pet Food & Water Dispenser', 'This feeder can be used by almost all pets, including cats, dogs and rabbits. This feeder works well even if you have more than one pet in your home.', 'Others', 200.00, '682ff38915e01.png'),
	('Pet Leash', 'Heavy-duty Braided nylon rope is inspired by mountain climbing gear designed for even the strongest pullers.', 'Others', 50.00, '682ff3996790b.png'),
	('Chain Pet Lash', 'Durable, heavy duty, and comfortable handling.', 'Others', 70.00, '682ff3a69d541.png');

SELECT * FROM products;
truncate products;

create table categories (
category_id INT AUTO_INCREMENT PRIMARY KEY,
category_title VARCHAR(100) NOT NULL
);

INSERT INTO categories (category_title)
VALUES  
	('Pet Foods'),
	('Pet Treats'),
	('Grooming Supplies'),
	('Medicine & Supplements'),
	('Others');
SELECT * FROM categories;

create table mail_subscribers (
	id INT AUTO_INCREMENT PRIMARY KEY,
	email VARCHAR(255) NOT NULL UNIQUE,  
	subscribed_at DATETIME NOT NULL
);
truncate mail_subscribers;
select * from mail_subscribers;