USE BookStore;

CREATE TABLE BOOK (
  BookID INT AUTO_INCREMENT PRIMARY KEY,
  ISBN VARCHAR(20) NOT NULL,
  PRICE INT,
  Title VARCHAR(100) NOT NULL,
  Author VARCHAR(50) NOT NULL,
  Genre VARCHAR(30) NOT NULL,
  Translator VARCHAR(50),
  Illustrator VARCHAR(50),
  PublicationDate DATE,
  Edition VARCHAR(50),
  NumberOfPrinting INT,
  Stock INT
);
ALTER TABLE BOOK AUTO_INCREMENT = 1001;


CREATE TABLE E_BOOK(
EBookID INT AUTO_INCREMENT PRIMARY KEY,
ISBN varchar(20) NOT NULL,
PRICE INT,
Title varchar(100) NOT NULL,
Author varchar(50) NOT NULL,
Genre varchar(30) NOT NULL,
Translator varchar(50),
Illustrator varchar(50),
PublicationDate Date,
Edition varchar(50),
NumberOfPrinting INT,
ContractExpirationDate INT
);
ALTER TABLE E_BOOK AUTO_INCREMENT = 5001;


CREATE TABLE MEMBER (
  MemberID INT PRIMARY KEY,
  First_Name VARCHAR(25) NOT NULL,
  Last_Name VARCHAR(25) NOT NULL,
  Email VARCHAR(50) NOT NULL,
  PhoneNumber VARCHAR(20) NOT NULL,
  DOB DATE
);

CREATE TABLE MemberAddress (
  AddressID INT PRIMARY KEY,
  MemberID INT,
  Street VARCHAR(100),
  City VARCHAR(50),
  Province VARCHAR(50),
  PostalCode INT,
  FOREIGN KEY (MemberID) REFERENCES MEMBER(MemberID)
);
CREATE TABLE Warehouse(
	WarehouseID INT PRIMARY KEY,
    Publisher varchar(20)
);
CREATE TABLE WarehouseAddress (
  AddressID INT PRIMARY KEY,
  WarehouseID INT,
  Street VARCHAR(100),
  City VARCHAR(50),
  Province VARCHAR(50),
  PostalCode INT,
  FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID)
);

CREATE TABLE Orders (
  OrderID INT AUTO_INCREMENT PRIMARY KEY,
  MemberID INT,
  OrderDate DATE,
  ShippingDate DATE,
  WarehouseID INT,
  FOREIGN KEY (MemberID) REFERENCES Member(MemberID),
  FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID)
);
CREATE TABLE OrdersDetail (
  OrdersDetail INT AUTO_INCREMENT PRIMARY KEY,
  OrderID INT,
  BookID INT NULL,
  EBookID INT NULL,
  Quantity INT NOT NULL,
  FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
  FOREIGN KEY (BookID) REFERENCES Book(BookID),
  FOREIGN KEY (EBookID) REFERENCES E_BOOK(EBookID)
);


CREATE TABLE Pre_Order (
  PreOrderID INT AUTO_INCREMENT PRIMARY KEY,
  MemberID INT,
  OrderDate DATE,
  ExpectedDeliveryDate DATE,
  TrackStatus VARCHAR(50),
  FOREIGN KEY (MemberID) REFERENCES Member(MemberID)
);

CREATE TABLE Pre_OrderDetail (
  PreOrderDetailID INT AUTO_INCREMENT PRIMARY KEY,
  PreOrderID INT,
  BookID INT NULL,
  Quantity INT NOT NULL,
  FOREIGN KEY (PreOrderID) REFERENCES Pre_Order(PreOrderID),
  FOREIGN KEY (BookID) REFERENCES Book(BookID)
);




CREATE TABLE Receipt (
  ReceiptNumber INT AUTO_INCREMENT PRIMARY KEY,
  PaymentMethod VARCHAR(50) NOT NULL,
  OrderID INT NULL,
  PreOrderID INT NULL,
  TaxID INT NOT NULL,
  Payee VARCHAR(50),
  TotalAmount INT,
  FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
  FOREIGN KEY (PreOrderID) REFERENCES Pre_Order(PreOrderID),
  CONSTRAINT chk_only_one_order CHECK (
    (OrderID IS NOT NULL AND PreOrderID IS NULL)
    OR (OrderID IS NULL AND PreOrderID IS NOT NULL)
  )
);

CREATE TABLE Receipt_Detail (
  Receipt_DetailID INT AUTO_INCREMENT PRIMARY KEY,
  ReceiptNumber INT,
  ISBN VARCHAR(20) NOT NULL,
  Title VARCHAR(100) NOT NULL,
  Qty INT NOT NULL,
  Price INT NOT NULL,
  SubTotal INT NOT NULL,
  FOREIGN KEY (ReceiptNumber) REFERENCES Receipt(ReceiptNumber)
);
CREATE TABLE E_Receipt(
E_ReceiptNumber INT AUTO_INCREMENT PRIMARY KEY,
OrderID INT,
PaymentMethod varchar(50) NOT NULL,
TaxID INT NOT NULL,
Payee varchar(50),
TotalAmount INT,
FOREIGN KEY (OrderID) REFERENCES Orders(OrderID)
);
CREATE TABLE E_Receipt_Detail(
E_Receipt_DetailID INT AUTO_INCREMENT PRIMARY KEY,
E_ReceiptNumber INT,
ISBN varchar(20) NOT NULL,
Title varchar(100) NOT NULL,
Qty INT NOT NULL,
Price INT NOT NULL,
SubTotal INT NOT NULL,
FOREIGN KEY (E_ReceiptNumber) REFERENCES  E_Receipt(E_ReceiptNumber)
);



CREATE TABLE Publisher (
	PublisherName VARCHAR(100),
  BookID INT,
  Editor VARCHAR(100),
  PRIMARY KEY (BookID, PublisherName),
  FOREIGN KEY (BookID) REFERENCES Book(BookID)
);





