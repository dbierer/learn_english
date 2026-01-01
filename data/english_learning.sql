PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE vocabulary (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT NOT NULL,
                english TEXT NOT NULL,
                khmer TEXT NOT NULL,
                pronunciation TEXT
            );
INSERT INTO vocabulary VALUES(1,'greetings','Hello','សួស្តី','soo-s-dey');
INSERT INTO vocabulary VALUES(2,'greetings','Good morning','អរុណសួស្តី','a-run soo-s-dey');
INSERT INTO vocabulary VALUES(3,'greetings','Good night','រាត្រីសួស្តី','rea-trey soo-s-dey');
INSERT INTO vocabulary VALUES(4,'greetings','Goodbye','លាហើយ','lea-haey');
INSERT INTO vocabulary VALUES(5,'greetings','Thank you','អរគុណ','or-kun');
INSERT INTO vocabulary VALUES(6,'greetings','You are welcome','មិនអីទេ','min ey te');
INSERT INTO vocabulary VALUES(7,'family','Mother','ម្តាយ','m-day');
INSERT INTO vocabulary VALUES(8,'family','Father','ឪពុក','ov-puk');
INSERT INTO vocabulary VALUES(9,'family','Sister','បងស្រី/ប្អូនស្រី','bong srey/p-oun srey');
INSERT INTO vocabulary VALUES(10,'family','Brother','បងប្រុស/ប្អូនប្រុស','bong pros/p-oun pros');
INSERT INTO vocabulary VALUES(11,'family','Grandmother','យាយ','yeay');
INSERT INTO vocabulary VALUES(12,'family','Grandfather','តា','ta');
INSERT INTO vocabulary VALUES(23,'colors','Red','ក្រហម','kra-hom');
INSERT INTO vocabulary VALUES(24,'colors','Blue','ខៀវ','khiev');
INSERT INTO vocabulary VALUES(25,'colors','Green','បៃតង','bai-tong');
INSERT INTO vocabulary VALUES(26,'colors','Yellow','លឿង','lueng');
INSERT INTO vocabulary VALUES(27,'colors','White','ស','s-aw');
INSERT INTO vocabulary VALUES(28,'colors','Black','ខ្មៅ','khmao');
INSERT INTO vocabulary VALUES(43,'house','house','ផ្ទះ','howss');
INSERT INTO vocabulary VALUES(44,'house','room','បន្ទប់','room');
INSERT INTO vocabulary VALUES(45,'house','living room','បន្ទប់ទទួលភ្ញៀវ','LIV-ing room');
INSERT INTO vocabulary VALUES(46,'house','bedroom','បន្ទប់គេង','BED-room');
INSERT INTO vocabulary VALUES(47,'house','kitchen','ផ្ទះបាយ','KITCH-ən');
INSERT INTO vocabulary VALUES(48,'house','bathroom','បន្ទប់ទឹក','BATH-room');
INSERT INTO vocabulary VALUES(49,'house','toilet','បង្គន់','TOY-lət');
INSERT INTO vocabulary VALUES(50,'house','dining room','បន្ទប់បរិភោគអាហារ','DY-ning room');
INSERT INTO vocabulary VALUES(51,'house','garage','រោងឡាន','guh-RAHZH');
INSERT INTO vocabulary VALUES(52,'house','balcony','រានហាល','BAL-kə-nee');
INSERT INTO vocabulary VALUES(53,'house','yard','ទីធ្លា','yard');
INSERT INTO vocabulary VALUES(54,'house','garden','សួន','GAR-dn');
INSERT INTO vocabulary VALUES(55,'house','door','ទ្វារ','dor');
INSERT INTO vocabulary VALUES(56,'house','window','បង្អួច','WIN-doh');
INSERT INTO vocabulary VALUES(57,'house','wall','ជញ្ជាំង','wol');
INSERT INTO vocabulary VALUES(58,'house','floor','ជាន់','flor');
INSERT INTO vocabulary VALUES(59,'house','roof','ដំបូល','roof');
INSERT INTO vocabulary VALUES(60,'house','stairs','ជណ្តើរ','stairz');
INSERT INTO vocabulary VALUES(61,'house','key','សោ','kee');
INSERT INTO vocabulary VALUES(62,'house','light','ភ្លើង','lyt');
INSERT INTO vocabulary VALUES(63,'house','lamp','ចង្កៀង','lamp');
INSERT INTO vocabulary VALUES(64,'house','fan','កង្ហារ','fan');
INSERT INTO vocabulary VALUES(65,'house','air conditioner','ម៉ាស៊ីនត្រជាក់','air kən-DISH-ən-ər');
INSERT INTO vocabulary VALUES(66,'house','table','តុ','TAY-bəl');
INSERT INTO vocabulary VALUES(67,'house','chair','កៅអី','chair');
INSERT INTO vocabulary VALUES(68,'house','sofa','សាឡុង','SOH-fə');
INSERT INTO vocabulary VALUES(69,'house','bed','គ្រែ','bed');
INSERT INTO vocabulary VALUES(70,'house','pillow','ខ្នើយ','PIL-oh');
INSERT INTO vocabulary VALUES(71,'house','blanket','ភួយ','BLANG-kət');
INSERT INTO vocabulary VALUES(72,'house','mirror','កញ្ចក់','MIR-er');
INSERT INTO vocabulary VALUES(73,'house','curtain','វាំងនន','KER-tn');
INSERT INTO vocabulary VALUES(74,'house','refrigerator','ទូទឹកកក','ri-FRIJ-uh-ray-ter');
INSERT INTO vocabulary VALUES(75,'house','stove','ចង្ក្រាន','stohv');
INSERT INTO vocabulary VALUES(76,'house','sink','អាងលាងចាន','singk');
INSERT INTO vocabulary VALUES(77,'house','shower','ផ្កាឈូក','SHOW-er');
INSERT INTO vocabulary VALUES(78,'house','soap','សាប៊ូ','sohp');
INSERT INTO vocabulary VALUES(79,'house','towel','កន្សែង','TOW-əl');
INSERT INTO vocabulary VALUES(80,'house','bucket','ធុង','BUCK-it');
INSERT INTO vocabulary VALUES(81,'house','broom','អំបោស','broom');
INSERT INTO vocabulary VALUES(82,'house','trash can','ធុងសំរាម','trash kan');
INSERT INTO vocabulary VALUES(84,'numbers','eleven','ដប់មួយ','i-LEH-vən');
INSERT INTO vocabulary VALUES(85,'numbers','twelve','ដប់ពីរ','twelv');
INSERT INTO vocabulary VALUES(86,'numbers','thirteen','ដប់បី','thur-TEEN');
INSERT INTO vocabulary VALUES(87,'numbers','fourteen','ដប់បួន','for-TEEN');
INSERT INTO vocabulary VALUES(88,'numbers','fifteen','ដប់ប្រាំ','fif-TEEN');
INSERT INTO vocabulary VALUES(89,'numbers','sixteen','ដប់ប្រាំមួយ','siks-TEEN');
INSERT INTO vocabulary VALUES(90,'numbers','seventeen','ដប់ប្រាំពីរ','sev-ən-TEEN');
INSERT INTO vocabulary VALUES(91,'numbers','eighteen','ដប់ប្រាំបី','ay-TEEN');
INSERT INTO vocabulary VALUES(92,'numbers','nineteen','ដប់ប្រាំបួន','nyn-TEEN');
INSERT INTO vocabulary VALUES(93,'numbers','twenty','ម្ភៃ','TWEN-tee');
INSERT INTO vocabulary VALUES(94,'numbers','one hundred','មួយរយ','wun HUN-drəd');
INSERT INTO vocabulary VALUES(95,'numbers','one thousand','មួយពាន់','wun THOW-zənd');
INSERT INTO vocabulary VALUES(96,'numbers','ten thousand','មួយម៉ឺន','ten THOW-zənd');
INSERT INTO vocabulary VALUES(97,'numbers','one hundred thousand','មួយសែន','wun HUN-drəd THOW-zənd');
INSERT INTO vocabulary VALUES(98,'numbers','one million','មួយលាន','wun MIL-yən');
CREATE TABLE conversations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT NOT NULL,
                speaker TEXT NOT NULL,
                english TEXT NOT NULL,
                khmer TEXT NOT NULL,
                order_num INTEGER
            );
INSERT INTO conversations VALUES(1,'introduction','Student','Hello! What is your name?','សួស្តី! តើអ្នកឈ្មោះអ្វី?',1);
INSERT INTO conversations VALUES(2,'introduction','Friend','My name is Sophea. What is your name?','ខ្ញុំឈ្មោះសុភា។ តើអ្នកឈ្មោះអ្វី?',2);
INSERT INTO conversations VALUES(3,'introduction','Student','My name is Dara. Nice to meet you!','ខ្ញុំឈ្មោះដារា។ រីករាយដែលបានជួបអ្នក!',3);
INSERT INTO conversations VALUES(4,'introduction','Friend','Nice to meet you too!','រីករាយដែលបានជួបអ្នកដែរ!',4);
INSERT INTO conversations VALUES(5,'shopping','Customer','How much is this?','នេះថ្លៃប៉ុន្មាន?',1);
INSERT INTO conversations VALUES(6,'shopping','Seller','This is 5 dollars.','នេះថ្លៃ ៥ ដុល្លារ។',2);
INSERT INTO conversations VALUES(7,'shopping','Customer','I will take it. Thank you!','ខ្ញុំយកវា។ អរគុណ!',3);
INSERT INTO conversations VALUES(8,'shopping','Seller','You are welcome!','មិនអីទេ!',4);
INSERT INTO conversations VALUES(9,'school','Teacher','Good morning, class!','អរុណសួស្តី សិស្សានុសិស្ស!',1);
INSERT INTO conversations VALUES(10,'school','Students','Good morning, teacher!','អរុណសួស្តី លោកគ្រូ!',2);
INSERT INTO conversations VALUES(11,'school','Teacher','Please open your books.','សូមបើកសៀវភៅរបស់អ្នក។',3);
INSERT INTO conversations VALUES(12,'school','Students','Yes, teacher!','បាទ/ចាស លោកគ្រូ!',4);
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('vocabulary',98);
INSERT INTO sqlite_sequence VALUES('conversations',12);
