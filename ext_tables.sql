CREATE TABLE tx_gdprextensionscomcm_domain_model_cookie (
	category varchar(255) NOT NULL DEFAULT '',
	domain varchar(255) NOT NULL DEFAULT '',
	platform varchar(255) NOT NULL DEFAULT '',
	name varchar(255) NOT NULL DEFAULT '',
	description Text NOT NULL DEFAULT '',
	session varchar(255) NOT NULL DEFAULT '',
    root_pid int(11) NOT NULL DEFAULT '0',
	type varchar(255) NOT NULL DEFAULT '',
	pages_list LONGTEXT NOT NULL DEFAULT '',
	expires varchar(255) NOT NULL DEFAULT '',
);

CREATE TABLE tx_gdprextensionscomcm_domain_model_externalresource (
	url Text NOT NULL DEFAULT '',
   external_resource_list LONGTEXT NOT NULL DEFAULT '',
   root_pid int(11) NOT NULL DEFAULT '0'  
);
CREATE TABLE tt_content (
	functional LONGTEXT NOT NULL DEFAULT '',
	non_functional LONGTEXT NOT NULL DEFAULT '',
	statistics LONGTEXT NOT NULL DEFAULT '',
	analytics LONGTEXT NOT NULL DEFAULT '',
	others LONGTEXT NOT NULL DEFAULT '',
	marketing LONGTEXT NOT NULL DEFAULT '',
	consent_header LONGTEXT NOT NULL DEFAULT '',
	consent_header_title varchar(255) NOT NULL DEFAULT ''
);
