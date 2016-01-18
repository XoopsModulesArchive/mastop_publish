* Introduction:
I needed a simple way of adding a dropdown button with my own data (keywords that users can insert into the templates they are editting with the editor).  It took me 15 minuts to come up with this, and allthough it works (for me), I'm not sure if it will sooth all your needs.

* Usage:
Add to your tinyMCE.init:
	plugin_keyword_list : "<keyword1>=<data1>;<keyword2>=<data2>..."
	theme_advanced_buttons1_add : "keyword"

and ofcourse add "keyword" to your "plugins" list



* Contact author:
Joshua Thijssen <jthijssen@noxlogic.nl>
