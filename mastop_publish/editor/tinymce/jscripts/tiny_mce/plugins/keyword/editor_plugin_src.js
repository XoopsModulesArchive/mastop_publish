/**
 * $RCSfile: editor_plugin_src.js,v $
 * $Revision: 1.3 $
 * $Date: 2007/03/10 20:49:12 $
 *
 * @author Joshua Thijssen
 *
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('keyword', 'en');

var TinyMCE_KeywordPlugin = {
	getInfo : function() {
		return {
			longname  : 'Keyword',
			author    : 'Joshua Thijssen <jthijssen@noxlogic.nl>',
			authorurl : 'http://www.noxlogic.nl',
			infourl   : 'http://www.noxlogic.nl',
			version   : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	getControlHTML : function(cn) {
		switch (cn) {
			case "keyword":
				var html = '<select id="{$editor_id}_keywordSelect" name="{$editor_id}_keywordSelect" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'KeywordSelect\',false,this.options[this.selectedIndex].value); this.selectedIndex=0;" class="mceSelectList"><option value="">{$lang_keyword_keyword_default}</option>';
				var keywords = tinyMCE.getParam ("plugin_keyword_list", false);
				if (! keywords) return "";
				keywords = keywords.split (";");

				for (i=0; i<keywords.length; i++) {
				  if (keywords[i] != '') {
				    var parts = keywords[i].split('=');
				    html += '<option value="' + parts[1] + '">' + parts[0] + '</option>';
				  }
				}
				html += '</select>';
				return html;
		}

		return "";
	},

	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "KeywordSelect":
				tinyMCE.execInstanceCommand (editor_id, 'mceInsertContent', false, value);
				return true;
		}

		// Pass to next handler in chain
		return false;
	},

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
	}
};

tinyMCE.addPlugin("keyword", TinyMCE_KeywordPlugin);
