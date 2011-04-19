CREATE TABLE `mpu_cfi_contentfiles` (
  `cfi_10_id` int(10) unsigned NOT NULL auto_increment,
  `cfi_30_nome` varchar(255) NOT NULL default '',
  `cfi_30_arquivo` varchar(100) NOT NULL default '',
  `cfi_30_mime` varchar(100) NOT NULL default '',
  `cfi_10_tamanho` int(10) NOT NULL default '0',
  `cfi_12_exibir` tinyint(1) NOT NULL default '1',
  `cfi_22_data` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cfi_10_id`)
) TYPE=MyISAM;

CREATE TABLE `mpu_fil_files` (
  `fil_10_id` int(10) unsigned NOT NULL auto_increment,
  `fil_30_nome` varchar(255) NOT NULL default '',
  `fil_30_arquivo` varchar(100) NOT NULL default '',
  `fil_30_mime` varchar(100) NOT NULL default '',
  `fil_10_tamanho` int(10) NOT NULL default '0',
  `fil_12_exibir` tinyint(1) NOT NULL default '1',
  `fil_22_data` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fil_10_id`)
) TYPE=MyISAM;
CREATE TABLE `mpu_med_media` (
  `med_10_id` int(10) unsigned NOT NULL auto_increment,
  `med_30_nome` varchar(255) NOT NULL default '',
  `med_30_arquivo` varchar(100) NOT NULL default '',
  `med_10_altura` int(4) unsigned NOT NULL default '0',
  `med_10_largura` int(4) unsigned NOT NULL default '0',
  `med_10_tamanho` int(8) unsigned NOT NULL default '0',
  `med_12_exibir` tinyint(1) NOT NULL default '1',
  `med_22_data` int(10) NOT NULL default '0',
  `med_10_tipo` int(1) NOT NULL default '1',
  PRIMARY KEY  (`med_10_id`)
) TYPE=MyISAM;
CREATE TABLE `mpu_mpb_mpublish` (
  `mpb_10_id` int(10) unsigned NOT NULL auto_increment,
  `mpb_10_idpai` int(10) NOT NULL default '0',
  `usr_10_uid` int(10) unsigned NOT NULL default '0',
  `mpb_30_menu` varchar(50) NOT NULL default '',
  `mpb_30_titulo` varchar(100) NOT NULL default '',
  `mpb_35_conteudo` longtext,
  `mpb_12_semlink` tinyint(1) NOT NULL default '0',
  `mpb_30_arquivo` varchar(255) default 'NULL',
  `mpb_11_visivel` tinyint(10) unsigned NOT NULL default '1',
  `mpb_11_abrir` tinyint(3) unsigned NOT NULL default '0',
  `mpb_12_comentarios` tinyint(1) NOT NULL default '0',
  `mpb_12_exibesub` tinyint(1) NOT NULL default '1',
  `mpb_12_recomendar` tinyint(1) NOT NULL default '1',
  `mpb_12_imprimir` tinyint(1) NOT NULL default '1',
  `mpb_22_criado` int(10) unsigned NOT NULL default '0',
  `mpb_22_atualizado` int(10) unsigned NOT NULL default '0',
  `mpb_10_ordem` int(10) unsigned default '0',
  `mpb_10_contador` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mpb_10_id`),
  KEY `mpb_10_idpai` (`mpb_10_idpai`)
) TYPE=MyISAM;
