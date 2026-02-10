DROP TABLE affecter CASCADE CONSTRAINTS;
DROP TABLE commande CASCADE CONSTRAINTS;
DROP TABLE reservation CASCADE CONSTRAINTS;
DROP TABLE hotesse CASCADE CONSTRAINTS;
DROP TABLE service CASCADE CONSTRAINTS;
DROP TABLE cabine CASCADE CONSTRAINTS;

CREATE TABLE cabine (
    numcab NUMBER(4),
    nbplace NUMBER(2),
    CONSTRAINT pk_cabine PRIMARY KEY (numcab)
);

CREATE TABLE service (
    numserv NUMBER(4),
    libelle VARCHAR2(40),
    prixunit NUMBER(6,2),
    nbrinterventions NUMBER(2), -- Stock dispo par jour
    CONSTRAINT pk_service PRIMARY KEY (numserv)
);

CREATE TABLE hotesse (
    numhot NUMBER(2),
    email VARCHAR2(255),
    passwd VARCHAR2(255),
    nomserv VARCHAR2(25), -- Nom et Prénom
    grade VARCHAR2(20),   -- 'hotesse' ou 'gestionnaire'
    CONSTRAINT pk_hotesse PRIMARY KEY (numhot),
    CONSTRAINT uk_email UNIQUE (email) -- Important pour le login !
);

CREATE TABLE reservation (
    numres NUMBER(4),
    numcab NUMBER(4),
    datres DATE,
    nbpers NUMBER(2),
    datpaie DATE,
    modpaie VARCHAR2(15),
    montcom NUMBER(8,2),
    CONSTRAINT pk_reservation PRIMARY KEY (numres),
    CONSTRAINT fk_res_cabine FOREIGN KEY (numcab) REFERENCES cabine (numcab)
);

CREATE TABLE commande (
    numres NUMBER(4),
    numserv NUMBER(4),
    nbrinterventions NUMBER(2),
    CONSTRAINT pk_commande PRIMARY KEY (numres, numserv),
    CONSTRAINT fk_com_res FOREIGN KEY (numres) REFERENCES reservation (numres),
    CONSTRAINT fk_com_serv FOREIGN KEY (numserv) REFERENCES service (numserv)
);

CREATE TABLE affecter (
    numcab NUMBER(4),
    dataff DATE,
    numhot NUMBER(2),
    CONSTRAINT pk_affecter PRIMARY KEY (numcab, dataff),
    CONSTRAINT fk_aff_cabine FOREIGN KEY (numcab) REFERENCES cabine (numcab),
    CONSTRAINT fk_aff_hot FOREIGN KEY (numhot) REFERENCES hotesse (numhot)
);


-- Tuples de cabine
insert into cabine values(10,4);
insert into cabine values(11,6);
insert into cabine values(12,8);
insert into cabine values(13,4);
insert into cabine values(14,6);
insert into cabine values(15,4);
insert into cabine values(16,4);
insert into cabine values(17,6);
insert into cabine values(18,2);
insert into cabine values(19,4);

-- Tuples de service
insert into service values(1,'soins visage',90,25);
insert into service values(2,'epilation',90,25);
insert into service values(3,'soins mains',90,35);
insert into service values(4,'soins pieds',90,62);
insert into service values(5,'massage classique',90,15);
insert into service values(6,'soins amincissants',90,21);
insert into service values(7,'soins fessiers',90,25);
insert into service values(8,'soins jambes',90,30);
insert into service values(9,'soins sourcils',90,58);
insert into service values(10,'manicure',90,42);
insert into service values(11,'massage asiatique',90,68);
insert into service values(12,'massage orientale',90,56);
insert into service values(13,'maquillage',90,15);
insert into service values(14,'sauna',90,18);
insert into service values(15,'soins pour cheveux',90,70);
insert into service values(16,'massage pour veterans',90,61);

-- Tuples de hotesse
insert into hotesse values(1,'user1@mail.com','$#;§èm$$$$$0','Tutus Peter','gestionnaire');
insert into hotesse values(2,'user2@mail.com','$xy#;§èm$$$$$1','Lilo Vito','hotesse');
insert into hotesse values(3,'user3@mail.com','$ab#;§èm$$$$$2','Don Carl','hotesse');
insert into hotesse values(4,'user4@mail.com','$cd#;§èm$$$$$3','Leo Jon','hotesse');
insert into hotesse values(5,'user5@mail.com','$mm#;§èm$$$$$4','Dean Geak','gestionnaire');

-- Tuples de reservation
insert into reservation values(100,10,to_date('10/09/2021 19:00','dd/mm/yyyy hh24:mi'),2,to_date('10/09/2021 20:50','dd/mm/yyyy hh24:mi'),'Carte',null);
insert into reservation values(101,11,to_date('10/09/2021 20:00','dd/mm/yyyy hh24:mi'),4,to_date('10/09/2021 21:20','dd/mm/yyyy hh24:mi'),'Chèque',null);
insert into reservation values(102,17,to_date('10/09/2021 18:00','dd/mm/yyyy hh24:mi'),2,to_date('10/09/2021 20:55','dd/mm/yyyy hh24:mi'),'Carte',null);
insert into reservation values(103,12,to_date('10/09/2021 19:00','dd/mm/yyyy hh24:mi'),2,to_date('10/09/2021 21:10','dd/mm/yyyy hh24:mi'),'Espèces',null);
insert into reservation values(104,18,to_date('10/09/2021 19:00','dd/mm/yyyy hh24:mi'),1,to_date('10/09/2021 21:00','dd/mm/yyyy hh24:mi'),'Chèque',null);
insert into reservation values(105,10,to_date('10/09/2021 19:00','dd/mm/yyyy hh24:mi'),2,to_date('10/09/2021 20:45','dd/mm/yyyy hh24:mi'),'Carte',null);
insert into reservation values(106,14,to_date('11/10/2021 19:00','dd/mm/yyyy hh24:mi'),2,to_date('11/10/2021 22:45','dd/mm/yyyy hh24:mi'),'Carte',null);

-- Tuples de commande
insert into commande values(100,4,2);
insert into commande values(100,5,2);
insert into commande values(100,13,1);
insert into commande values(100,3,1);
insert into commande values(101,7,2);
insert into commande values(101,16,2);
insert into commande values(101,12,2);
insert into commande values(101,15,2);
insert into commande values(101,2,2);
insert into commande values(101,3,2);
insert into commande values(102,1,2);
insert into commande values(102,10,2);
insert into commande values(102,14,2);
insert into commande values(102,2,1);
insert into commande values(102,3,1);
insert into commande values(103,9,2);
insert into commande values(103,14,2);
insert into commande values(103,2,1);
insert into commande values(103,3,1);
insert into commande values(104,7,1);
insert into commande values(104,11,1);
insert into commande values(104,14,1);
insert into commande values(104,3,1);
insert into commande values(105,3,2);
insert into commande values(106,3,2);

-- Tuples de Affecter
insert into affecter values(10,'10/09/2021',1);
insert into affecter values(11,'10/09/2021',1);
insert into affecter values(12,'10/09/2021',1);
insert into affecter values(17,'10/09/2021',2);
insert into affecter values(18,'10/09/2021',2);
insert into affecter values(15,'10/09/2021',3);
insert into affecter values(16,'10/09/2021',3);
insert into affecter values(10,'11/09/2021',1);

COMMIT;