To use the visual editor of Mastop Publish in entire site, you need to copy the folder 'xoopseditor' to the 'class' folder inside xoops root path (do it only if the folder 'xoopseditor' ALREADY EXISTS inside 'class' folder).
If you have no 'xoopseditor' folder inside 'class', download it from:
http://xoops.org.cn/uploads/mydownloads/xoops/xoops-class-xoopseditor.zip
After copy the folder, open the file /class/xoopsform/formdhtmltextarea.php and search this line (near line 74):
var $htmlEditor = array();
Change to this:
var $htmlEditor = array('XoopsFormMPublishTextArea', '/class/xoopseditor/mastop_publish/formmpublishtextarea.php');
(DO IT AFTER INSTALL THE MASTOP PUBLISH MODULE)
