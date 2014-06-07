CREATE TABLE USUARIO(
	ID int not null AUTO_INCREMENT COMMENT 'ID do Usuário, pk, autoincrement',
	Nome char(30) not null COMMENT 'Primeiro nome',
	Sobrenome char(30) COMMENT 'O resto do nome',
	Email char(50) not null COMMENT 'E-mail, pk',
	Username char(50) not null COMMENT 'Nome de usuario, pk',
	Senha char(50) not null COMMENT 'senha escolhida pelo usuario',
	
	CONSTRAINT pk_usuario PRIMARY KEY (Email,Username,ID)
	
);

CREATE TABLE ACESSO(
	ID int not null AUTO_INCREMENT COMMENT 'ID acesso, pk, autoincrement',
	ID_Usuario int not null COMMENT 'Id do usuario que acessou, fk',
	Data_Acesso char(11) not null COMMENT 'Data de acesso, geralmente usa-se sysdate como defaut',
	IP char(17) COMMENT 'IP de acesso usado pelo usuario,',
	
	CONSTRAINT pk_acesso PRIMARY KEY (ID),
	CONSTRAINT fk_acesso_x_usuario FOREIGN KEY (ID_Usuario) REFERENCES USUARIO(ID)
	
);

CREATE TABLE CONTA_A_PAGAR(
	ID int not null AUTO_INCREMENT COMMENT 'ID de contas a pagar, pk, autoincrement',
	Valor float not null COMMENT 'Valor referente a conta',
	Status char(15) not null DEFAULT 'Pendente' COMMENT 'Status da conta, cc',
	OBS char(100) COMMENT 'Obs referente a conta',
	Data_Criacao char(11) not null COMMENT 'Data criação da conta, geralmente usa-se sysdate',
	Data_Pago char(11) COMMENT 'Quando altera-se a conta para paga, edita-se este campo',
	ID_Usuario int not null COMMENT 'Id do usuario que possui a conta, FK',
	
	CONSTRAINT ck_Status CHECK (Status in ('Pendente','Pago')),
	CONSTRAINT pk_ID PRIMARY KEY (ID),
	CONSTRAINT fk_Conta_a_Pagar_Usuario FOREIGN KEY (ID_Usuario) REFERENCES USUARIO(ID)
	
);

CREATE TABLE CONTA_A_RECEBER(
	ID int not null AUTO_INCREMENT COMMENT 'ID de contas a recever, pk, autoincrement',
	Valor float not null COMMENT 'Valor refetene a conta a receber',
	Status char(15) not null DEFAULT 'Pendente' COMMENT 'Status da conta, cc',
	OBS char(100) COMMENT 'Obs refetente a conta',
	Data_Criacao char(11) not null COMMENT 'Data de criação da conta, geralmente usa-se sysdate',
	Data_Recebido char(11) COMMENT 'Quando altera-se o status para recebido, altera-se este campo',
	ID_Usuario int not null COMMENT 'ID do usuario que possui a conta a receber, fk',
	
	CONSTRAINT ck_Status CHECK (Status in ('Pendente','Recebido')),
	CONSTRAINT pk_ID PRIMARY KEY (ID),
	CONSTRAINT fk_Conta_a_Receber_Usuario FOREIGN KEY (ID_Usuario) REFERENCES USUARIO(ID)
);