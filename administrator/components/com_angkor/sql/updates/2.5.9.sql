
CREATE TABLE IF NOT EXISTS `#__angkor_emails` 
(  
	`id` int(11) NOT NULL auto_increment,  
	`code` varchar(50) default NULL,  
	`subject` varchar(255) default NULL,  
	`body` text,
	`sender_name` varchar(64) not null default '{sendername}',
	`sender_email` varchar(64) not null default '{senderemail}',
	`lang` CHAR(2) default NULL,
	`embed_image` TINYINT NULL,
	PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__angkor_email_fields` 
(  
	`id` int(11) NOT NULL auto_increment,  
	`code` varchar(255) default NULL, 	
	`field_name` varchar(255) default NULL,  
	PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__angkor_css` 
(  
	`id` int(11) NOT NULL auto_increment,  
	`css` TEXT DEFAULT NULL,
	PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__angkor_emails` (`id`, `code`, `subject`, `body`, `sender_name`, `sender_email`) VALUES
(1, 'SEND_MSG_ACTIVATE', 'confirmacion TransExpress', '<table border="0" cellpadding="0" cellspacing="0" width="60%">\r\n<tbody>\r\n<tr>\r\n<td colspan="2"><img style="display: block; margin-left: auto; margin-right: auto;" alt="TransExpress" src="images/stories/email/logo.png" height="57" width="186" /></td>\r\n</tr>\r\n<tr>\r\n<td colspan="2">\r\n<div style="background: #f6f6f6; border: 1px solid #d7d7d7; padding: 10px 20px 20px 20px; font-size: 12px; color: #231f20;">\r\n<div style="border: 1px solid #d7d7d7; background-color: #ffffff;">\r\n<div style="font-size: 12px; color: #000; padding: 10px 10px 5px 10px;">\r\n<p>{name}:</p>\r\n<p>Bienvenido(a), estás a unos pasos de activar tu cuenta.</p>\r\n<p><br /> <b>Pasos para activar tu Casilla Miami:<br /></b></p>\r\n<p>1) Haz Click en el link:<br /><br />{activationurl}</p>\r\n<p>2) Ingresa nuevamente tu clave</p>\r\n<p>3) Activa tu cuenta<br /><br />Tu nombre de usuario es el número de tu casilla: <span style="color: #008000;">{comp1_casilleroidalias}</span><br /><br />Tu password es: <span style="color: #008000;">{password}</span></p>\r\n<div style="font-size: 12px; color: #000000; padding: 2px 10px;">\r\n<p><b>Tu dirección en Miami para tus compras por Internet es:</b></p>\r\n</div>\r\n<div style="font-size: 12px; color: #000000; padding: 2px 10px;">{name}</div>\r\n<div style="font-size: 12px; color: #000000; padding: 2px 10px;">{comp1_casilleroidalias}</div>\r\n<div style="font-size: 12px; color: #000000; padding: 2px 10px;">{strTransexpressAddressOne}</div>\r\n<div style="font-size: 12px; color: #000000; padding: 2px 10px;">{strTransexpressAddressTwo}</div>\r\n<div style="font-size: 12px; color: #000000; padding: 2px 10px;">{strTransexpressAddressThree}</div>\r\n</div>\r\n<div></div>\r\n</div>\r\n</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>', '{sendername}', '{senderemail}'),
(2, 'SEND_MSG', 'Account Details for {name} at {sitename}', 'Hello {name},<br><br>Thank you for registering at {sitename}.<br><br>You may now Login to {siteurl} using the username and password you registered with.', '{sendername}', '{senderemail}'),
(3, 'SEND_MSG_ADMIN', 'Account Details for {name} at {sitename}', 'Hello {adminname} ,<br><br>A new User has registered at {sitename}.<br>This e-mail contains their details:<br><br>Name - {name}<br>e-mail - {email}<br>Username - {username}<br><br>Please do not respond to this message as it is automatically generated and is for information purposes only.', '{sendername}', '{senderemail}'),
(4, 'USERNAME_REMINDER', 'Your {sitename} username', 'Hello,<br><br>A username reminder has been requested for your {sitename} account.<br><br>Your username is {username}.<br><br>To login to your account, click on the link below.<br><br>{siteurl}<br><br>Thank you.', '{sendername}', '{senderemail}'),
(5, 'PASSWORD_RESET_CONFIRMATION', 'Your {sitename} password reset request', 'Hello {username},<br><br>a request has been made to reset your {sitename} account password.  To reset your password, you will need to submit this token in order to verify that the request was legitimate.<br><br>The token is {token} .<br><br>Click on the URL below to enter the token and proceed to resetting your password.<br><br>{siteurl}<br><br>Thank you.', '{sendername}', '{senderemail}'),
(6, 'SEND_MSG_AUTHORIZE', 'Your {username} authorization', 'Hello {username},<br><br>Your request was sent to the administrator. You account will be activated by administrator.<br/><br/> Account Details<br>Username: {username}<br>Password: {password}<br><br>Thank you.', '{sendername}', '{senderemail}'),
(7, 'SEND_MSG_TO_CONTACT', '{sitename}: {subject}', 'Hello {r_name}, <br>You have an email from {s_name}  {s_email} via {siteurl}<br><br>Message:<br>{message}', '{sendername}', '{senderemail}'),
(8, 'SEND_COPY_MSG_TO_USER', 'Copy of: {subject}', '<div>Hola {user_name},<br /><br /> La pre-alert fue posteada correctamente</div>\r\n<div></div>\r\n<div>{prealert_pdf_linktext}</div>\r\n<div></div>\r\n<div>{prealert_pdf_link}</div>', '{sendername}', '{senderemail}'),
(9, 'SEND_COPY_MSG_TO_ADMIN', 'Copia de Alerta de Cliente: {userid}', '<div>\r\n<div>Hello  {user_name},<br /><br /> A user has posted a prealert.</div>\r\n<div></div>\r\n<div>{prealert_pdf_linktext}<br /><br />{prealert_pdf_link}</div>\r\n</div>', '{sendername}', '{senderemail}'),
(10, 'ADD_NEW_USER', 'New User Details', 'Hello {name},<br><br>You have been added as a User to {sitename} by an Administrator.<br><br>This e-mail contains your username and password to log in to {siteurl}<br><br>Username: {username}<br>Password: {password}<br><br>Please do not respond to this message as it is automatically generated and is for information purposes only.', '{sendername}', '{senderemail}'),
(11, 'PREALERT_MSG_USER', 'Prealert User Message', 'Hello {name},<br><br>Your prealert has been posted successfully. Below is the pdf and image that you have uploaded.', '{sendername}', '{senderemail}'),
(12, 'PREALERT_MSG_ADMIN', 'Prealert Admin Message', 'Hello {name},<br><br>A user has posted prealert . Below is the pdf and image.', '{sendername}', '{senderemail}');

INSERT INTO `#__angkor_email_fields` (`code`, `id`, `field_name`) VALUES
('SEND_MSG_ACTIVATE', 1, '{name}'),
('SEND_MSG_ACTIVATE', 2, '{username}'),
('SEND_MSG_ACTIVATE', 3, '{password}'),
('SEND_MSG_ACTIVATE', 4, '{sitename}'),
('SEND_MSG_ACTIVATE', 5, '{siteurl}'),
('SEND_MSG_ACTIVATE', 6, '{activationurl}'),
('SEND_MSG_ACTIVATE', 7, '{loginurl}'),
('SEND_MSG_ACTIVATE', 8, '{email}'),
('SEND_MSG', 9, '{name}'),
('SEND_MSG', 10, '{username}'),
('SEND_MSG', 11, '{password}'),
('SEND_MSG', 12, '{sitename}'),
('SEND_MSG', 13, '{siteurl}'),
('SEND_MSG', 14, '{activationurl}'),
('SEND_MSG', 15, '{loginurl}'),
('SEND_MSG', 16, '{email}'),
('SEND_MSG_ADMIN', 17, '{adminname}'),
('SEND_MSG_ADMIN', 18, '{name}'),
('SEND_MSG_ADMIN', 19, '{username}'),
('SEND_MSG_ADMIN', 20, '{password}'),
('SEND_MSG_ADMIN', 21, '{sitename}'),
('SEND_MSG_ADMIN', 22, '{siteurl}'),
('SEND_MSG_ADMIN', 23, '{activationurl}'),
('SEND_MSG_ADMIN', 24, '{loginurl}'),
('SEND_MSG_ADMIN', 25, '{email}'),
('USERNAME_REMINDER', 26, '{username}'),
('USERNAME_REMINDER', 27, '{sitename}'),
('USERNAME_REMINDER', 28, '{siteurl}'),
('PASSWORD_RESET_CONFIRMATION', 29, '{name}'),
('PASSWORD_RESET_CONFIRMATION', 30, '{username}'),
('PASSWORD_RESET_CONFIRMATION', 31, '{token}'),
('PASSWORD_RESET_CONFIRMATION', 32, '{sitename}'),
('PASSWORD_RESET_CONFIRMATION', 33, '{siteurl}'),
('SEND_MSG_AUTHORIZE', 34, '{username}'),
('SEND_MSG_AUTHORIZE', 35, '{password}'),
('SEND_MSG_TO_CONTACT', 36, '{s_name}'),
('SEND_MSG_TO_CONTACT', 37, '{s_email}'),
('SEND_MSG_TO_CONTACT', 38, '{r_name}'),
('SEND_MSG_TO_CONTACT', 39, '{r_email}'),
('SEND_MSG_TO_CONTACT', 40, '{siteurl}'),
('SEND_MSG_TO_CONTACT', 41, '{sitename}'),
('SEND_MSG_TO_CONTACT', 42, '{message}'),
('SEND_MSG_TO_CONTACT', 43, '{subject}'),
('SEND_COPY_MSG_TO_USER', 44, '{s_name}'),
('SEND_COPY_MSG_TO_USER', 45, '{s_email}'),
('SEND_COPY_MSG_TO_USER', 46, '{r_name}'),
('SEND_COPY_MSG_TO_USER', 47, '{r_email}'),
('SEND_COPY_MSG_TO_USER', 48, '{subject}'),
('SEND_COPY_MSG_TO_USER', 49, '{message}'),
('SEND_COPY_MSG_TO_USER', 50, '{siteurl}'),
('SEND_COPY_MSG_TO_USER', 51, '{sitename}'),
('SEND_COPY_MSG_TO_ADMIN', 52, '{s_name}'),
('SEND_COPY_MSG_TO_ADMIN', 53, '{s_email}'),
('SEND_COPY_MSG_TO_ADMIN', 54, '{r_name}'),
('SEND_COPY_MSG_TO_ADMIN', 55, '{r_email}'),
('SEND_COPY_MSG_TO_ADMIN', 56, '{message}'),
('SEND_COPY_MSG_TO_ADMIN', 57, '{adminname}'),
('SEND_COPY_MSG_TO_ADMIN', 58, '{subject}'),
('SEND_COPY_MSG_TO_ADMIN', 59, '{siteurl}'),
('ADD_NEW_USER', 60, '{name}'),
('ADD_NEW_USER', 61, '{username}'),
('ADD_NEW_USER', 62, '{password}'),
('ADD_NEW_USER', 63, '{sitename}'),
('ADD_NEW_USER', 64, '{siteurl}'),
('SENDARTICLE', 65, '{sitename}'),
('SENDARTICLE', 66, '{name}'),
('SENDARTICLE', 67, '{email}'),
('SENDARTICLE', 68, '{article_link)}');
