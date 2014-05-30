CREATE IF NOT EXISTS TABLE USUARIO(
	ID int not null AUTO_INCREMENT,
	Nome char(30) not null,
	Sobrenome char(30),
	Email char(50) not null,
	Username char(50) not null,
	Senha char(50) not null,
	
	CONSTRAINT pk_usuario PRIMARY KEY (Email,Username,ID)
	
);

CREATE IF NOT EXISTS TABLE ACESSO(
	ID int not null AUTO_INCREMENT,
	ID_Usuario int not null,
	Data_Acesso date not null,
	IP char(17),
	
	CONSTRAINT pk_acesso PRIMARY KEY (ID),
	CONSTRAINT fk_acesso_x_usuario FOREIGN KEY (ID_Usuario) REFERENCES USUARIO(ID)
	
);

CREATE IF NOT EXISTS TABLE CONTA_A_PAGAR(
	ID int not null AUTO_INCREMENT,
	Valor float not null,
	Status char(15) not null DEFAULT 'Pendente',
	OBS char(100),
	Data_Criacao date not null,
	Data_Pago date,
	ID_Usuario int not null,
	
	CONSTRAINT ck_Status CHECK (Status in ('Pendente','Pago')),
	CONSTRAINT pk_ID PRIMARY KEY (ID)
	CONSTRAINT fk_Conta_a_Pagar_Usuario FOREIGN KEY (ID_Usuario) REFERENCES USUARIO(ID)
	
);

CREATE IF NOT EXISTS TABLE CONTA_A_RECEBER(
	ID int not null AUTO_INCREMENT,
	Valor float not null,
	Status char(15) not null DEFAULT 'Pendente',
	OBS char(100),
	Data_Criacao date not null,
	Data_Pago date,
	ID_Usuario int not null,
	
	CONSTRAINT ck_Status CHECK (Status in ('Pendente','Recebido')),
	CONSTRAINT pk_ID PRIMARY KEY (ID)
	CONSTRAINT fk_Conta_a_Receber_Usuario FOREIGN KEY (ID_Usuario) REFERENCES USUARIO(ID)
);