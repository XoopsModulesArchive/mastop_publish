<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo de Tradução para Informações do Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================

//V1.0
define("MPU_MOD_NOME", "Mastop Publish");
define("MPU_MOD_DESC", "Create static pages for your site!");
define("MPU_MOD_DIR", "mastop_publish");
define("MPU_MOD_TABELA1", "mpu_mpb_mpublish");
define("MPU_MOD_TABELA2", "mpu_fil_files");
define("MPU_MOD_TABELA3", "mpu_med_media");
define("MPU_MOD_TABELA4", "mpu_cfi_contentfiles");
define("MPU_MOD_TEMPLATE1", "mpu_index.tpl.html");
define("MPU_MOD_TEMPLATE1_DESC", "Page layout");
define("MPU_MOD_BLOCOS", "Blocks");


define("MPU_MOD_BLOCO1", "Menu");
define("MPU_MOD_BLOCO1_DESC", "Css Menu with Submenus");
define("MPU_MOD_BLOCO1_FILE", "mpu_menucss.bloco.php");
define("MPU_MOD_BLOCO1_SHOW", "mpu_menucss_exibe");
define("MPU_MOD_BLOCO1_EDIT", "mpu_menucss_edita");
define("MPU_MOD_BLOCO1_TEMPLATE", "mpu_menucss.block.tpl.html");

define("MPU_MOD_BLOCO2", "Navigation");
define("MPU_MOD_BLOCO2_DESC", "Navigation Bar");
define("MPU_MOD_BLOCO2_FILE", "mpu_navigation.bloco.php");
define("MPU_MOD_BLOCO2_SHOW", "mpu_navigation_exibe");
define("MPU_MOD_BLOCO2_EDIT", "mpu_navigation_edita");

define("MPU_MOD_BLOCO3", "See also");
define("MPU_MOD_BLOCO3_DESC", "Show the pages beloging to the same category");
define("MPU_MOD_BLOCO3_FILE", "mpu_related.bloco.php");
define("MPU_MOD_BLOCO3_SHOW", "mpu_related_exibe");
define("MPU_MOD_BLOCO3_EDIT", "mpu_related_edita");
define("MPU_MOD_BLOCO3_TEMPLATE", "mpu_related.block.tpl.html");


define("MPU_MOD_BLOCO4", "Tree Menu");
define("MPU_MOD_BLOCO4_DESC", "Tree Menu with Submenus");
define("MPU_MOD_BLOCO4_FILE", "mpu_menutree.bloco.php");
define("MPU_MOD_BLOCO4_SHOW", "mpu_menutree_exibe");
define("MPU_MOD_BLOCO4_EDIT", "mpu_menutree_edita");
define("MPU_MOD_BLOCO4_TEMPLATE", "mpu_menutree.block.tpl.html");


define("MPU_MOD_BLOCO5", "Horizontal Menu");
define("MPU_MOD_BLOCO5_DESC", "CSS Horizontal Menu with Submenus");
define("MPU_MOD_BLOCO5_FILE", "mpu_menuhor.bloco.php");
define("MPU_MOD_BLOCO5_SHOW", "mpu_menuhor_exibe");
define("MPU_MOD_BLOCO5_EDIT", "mpu_menuhor_edita");
define("MPU_MOD_BLOCO5_TEMPLATE", "mpu_menuhor.block.tpl.html");

define("MPU_MOD_BUSCA_FUNC", "mpu_mpublish_busca");
define("MPU_MOD_WYSIWYG", "Use visual editor to create pages?");
define("MPU_MOD_WYSIWYG_DESC", "With a visual editor (WYSIWYG) you can create pages with all HTML resources faster, without any programming knowledge.");
define("MPU_MOD_WYSIWYG_PATH", "Path Editor");
define("MPU_MOD_WYSIWYG_BT1B", "Editor buttons - Start Line 1");
define("MPU_MOD_WYSIWYG_BT1B_DESC", "Buttons that will be shown in the beginning of the first line in the visual editor.");
define("MPU_MOD_WYSIWYG_BT1", "Editor buttons - End Line 1");
define("MPU_MOD_WYSIWYG_BT1_DESC", "Buttons that will be shown in the end of the first line in the visual editor.");
define("MPU_MOD_WYSIWYG_BT2B", "Editor buttons - Start Line 2");
define("MPU_MOD_WYSIWYG_BT2B_DESC", "Buttons that will be shown in the beginning of the seccond line in the visual editor.");
define("MPU_MOD_WYSIWYG_BT2", "Editor buttons - End Line 2");
define("MPU_MOD_WYSIWYG_BT2_DESC", "Buttons that will be shown in the end of the first line in the visual editor.");
define("MPU_MOD_WYSIWYG_BT3B", "Editor buttons - Start Line 3");
define("MPU_MOD_WYSIWYG_BT3B_DESC", "Buttons that will be shown in the beginning of the third line in the visual editor.");
define("MPU_MOD_WYSIWYG_BT3", "Editor buttons - End Line 3");
define("MPU_MOD_WYSIWYG_BT3_DESC", "Buttons that will be shown in the end of the first line in the visual editor.");
define("MPU_MOD_WYSIWYG_BT4", "Editor buttons - Line 4");
define("MPU_MOD_WYSIWYG_BT4_DESC", "Buttons that will be shown in the fourth line in the visual editor.");
define("MPU_MOD_WYSIWYG_PLUGINS", "Editor Plugins");
define("MPU_MOD_WYSIWYG_PLUGINS_DESC", "Enter the plugins that will be used in the visual editor (separated by commas ',')");
define("MPU_MOD_WYSIWYG_PATH_DESC", "Enter the path for TinyMCE starting at the root of your site (No dash bar at the end!).");
define("MPU_MOD_WYSIWYG_LANG", "Default language for the Editor");
define("MPU_MOD_WYSIWYG_LANG_DESC", "Enter the language file anme for the visual editor (Ex.: en). To download more language packages, <a href='http://tinymce.moxiecode.com/language.php' target='_blank'>click here</a>.");
define("MPU_MOD_NOMES_ID", "Use text instead of the ID in the URL?");
define("MPU_MOD_NOMES_ID_DESC", "By choosing 'Yes' the menu text will be used instead of the ID in the URL's content. The system accepts both as default to load a page. This setup will only affect the links generated by the system.");
define("MPU_MOD_MMAXFILESIZE", "Maximum size for sending medias.");
define("MPU_MOD_MMAXFILESIZE_DESC", "Values in Kbytes");
define("MPU_MOD_MAXFILESIZE", "Maximum size for sending files.");
define("MPU_MOD_MAXFILESIZE_DESC", "Values in Kbytes");
define("MPU_MOD_GZIP", "Use GZIP compression in the Editor?");
define("MPU_MOD_GZIP_DESC", "If your server supports GZIP compression, this resource makes use of a compacted javascript that allows the editor to be loaded faster.<br /><b>Warning:</b> So that this resource can run correctly, the root directory of the visual editor (defined above, in the configuration parameter '".MPU_MOD_WYSIWYG_PATH."') must have write permissions!");
define("MPU_MOD_CONTENTMIMES", "Allowed extensions in the content file manager.");
define("MPU_MOD_CONTENTMIMES_DESC", "Select the allowed extensions for upload in the content file manager. Keep the <b>CTRL</b> key pressed to select more than one option.");
define("MPU_MOD_MIMETYPES", "Allowed extensions in the file manager.");
define("MPU_MOD_MIMETYPES_DESC", "Select the allowed extensions for upload in the file manager. Keep the <b>CTRL</b> key pressed to select more than one option.");
define("MPU_MOD_WYSIWYG_FRMTDATA", "Date format for the editor");
define("MPU_MOD_WYSIWYG_FRMTDATA_DESC", "<b>%d</b> stays for 'Day', <b>%m</b> stays for 'Month' and <b>%Y</b> stays for 'Year'");
define("MPU_MOD_WYSIWYG_FRMTHORA", "Time format for the editor");
define("MPU_MOD_WYSIWYG_FRMTHORA_DESC", "<b>%H</b> stays for 'Hour', <b>%M</b> stays for 'Minute' e <b>%S</b> stays for 'Second'");
define("MPU_MOD_IFRAME_WIDTH", "IFrames width");
define("MPU_MOD_IFRAME_WIDTH_DESC", "Define the width (in pixels) to be used with IFrame pages");
define("MPU_MOD_IFRAME_HEIGHT", "IFrames height");
define("MPU_MOD_IFRAME_HEIGHT_DESC", "Define the height (in pixels) to be used with IFrame pages");
define("MPU_MOD_RELATED", "Show links to related pages at the bottom of each page links para páginas relacionadas no final de cada página?");
define("MPU_MOD_RELATED_DESC", "This option allows to show links to pages that are in the same category that the current page. <br />You can disable this option if you already use the 'Related Pages' block");
define("MPU_MOD_NAVIGATION", "Show navigation bar at the top of pages?");
define("MPU_MOD_NAVIGATION_DESC", "This option allows the navigation bar to be shown at the top of each page. <br />You can disable this option if you already use the 'Navigation bar' block");
define("MPU_MOD_CAPTCHA", "Use CAPTCHA in the comments?");
define("MPU_MOD_CAPTCHA_DESC", "<a href='http://en.wikipedia.org/wiki/CAPTCHA' target='_blank'>CAPTCHA</a> is a technique that asks the user to write a sequence of letters or numbers that are shown in a box before posting a comment, to avoid that the system is used by crawlers (GD library is required).");
define("MPU_MOD_CAPTCHA_LABEL", "Security code");
define("MPU_MOD_CAPTCHA_ERROR", "Security code not valid!<br /> Try again");
define("MPU_MOD_HIGHLIGHT_SEARCH", "<b style='color: red'>The following search terms were highlighted:</b> ");

// Administração - Menu
define('MPU_MOD_MENU_ADD','Add Content');
define('MPU_MOD_MENU_LNK','Manage HTML files');
define('MPU_MOD_MENU_LST','Manage Contents');
define('MPU_MOD_MENU_MED','Medias');
define('MPU_MOD_MENU_GER','Manage');
define('MPU_MOD_MENU_FIL','Files');


//Module Paths
define('MPU_MEDIA_URL', XOOPS_URL."/modules/".MPU_MOD_DIR."/media");
define('MPU_MEDIA_PATH', XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/media");
define('MPU_FILES_URL', XOOPS_URL."/modules/".MPU_MOD_DIR."/files");
define('MPU_FILES_PATH', XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/files");
define('MPU_HTML_URL', XOOPS_URL."/modules/".MPU_MOD_DIR."/html");
define('MPU_HTML_PATH', XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/html");

//V1.1
define("MPU_MOD_CANEDIT", "Can the authors edit their own pages?");
define("MPU_MOD_CANEDIT_DESC", "Select 'yes so that the author can edit their own pages (even not beeing in the webmaster group).");
define("MPU_MOD_CANCREATE", "Can the authors create sub-pages?");
define("MPU_MOD_CANCREATE_DESC", "Select 'yes so that the author can create sub-pages inside their own pages (even not beeing in the webmaster group).");
define("MPU_MOD_CANDELETE", "Can the authors delete their own pages?");
define("MPU_MOD_CANDELETE_DESC", "Select 'yes so that the author can delete their own pages (even not beeing in the webmaster group) .");

define("MPU_MOD_BLOCO6", "Related Pages Menu");
define("MPU_MOD_BLOCO6_DESC", "Menu containing related pages in relation to the current one");
define("MPU_MOD_BLOCO6_FILE", "mpu_menurelated.bloco.php");
define("MPU_MOD_BLOCO6_SHOW", "mpu_menurelated_exibe");
define("MPU_MOD_BLOCO6_EDIT", "mpu_menurelated_edita");
define("MPU_MOD_BLOCO6_TEMPLATE", "mpu_menurelated.block.tpl.html");


define("MPU_MOD_WYSIWYG_BKG", "Use white background in the editor?");
define("MPU_MOD_WYSIWYG_BKG_DESC", "By activating this option the editor's background will be always white and willnot inherited the configurations from you current theme.");

define("MPU_MOD_HOME_ID", "Main Page ID");
define("MPU_MOD_HOME_ID_DESC", "Enter the Page ID that will open as the default page when entering the module. Leave it blank so that the last inserted page becomes the main page.");