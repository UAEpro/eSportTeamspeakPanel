<?php
// include this in header of index.php if page=editor


ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);
	
?>

<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>

		<link rel="stylesheet" href="/ePanel/bbcode/minified/themes/modern.min.css" type="text/css" media="all" />

		<script src="/ePanel/bbcode/development/jquery.sceditor.bbcode.js"></script>

		<style>
			html {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 13px;
			}
			form div {
				padding: .5em;
			}
			code:before {
				position: absolute;
				content: 'Code:';
				top: -1.35em;
				left: 0;
			}
			code {
				margin-top: 1.5em;
				position: relative;
				background: #eee;
				border: 1px solid #aaa;
				white-space: pre;
				padding: .25em;
				min-height: 1.25em;
			}
			code:before, code {
				display: block;
				text-align: left;
			}
			

		</style>

		<script>
			// Source: http://www.backalleycoder.com/2011/03/20/link-tag-css-stylesheet-load-event/
			var loadCSS = function(url, callback){
				var link = document.createElement('link');
				link.type = 'text/css';
				link.rel = 'stylesheet';
				link.href = url;
				link.id = 'theme-style';

				document.getElementsByTagName('head')[0].appendChild(link);

				var img = document.createElement('img');
				img.onerror = function(){
					if(callback) callback(link);
				}
				img.src = url;
			}

			$(document).ready(function() {
				var initEditor = function() {
					$("textarea").sceditor({
						plugins: 'bbcode',
						style: "./minified/jquery.sceditor.default.min.css"
					});
				};

				$("#theme").change(function() {
					var theme = "./minified/themes/modern.min.css";

					$("textarea").sceditor("instance").destroy();
					$("link:first").remove();
					$("#theme-style").remove();

					loadCSS(theme, initEditor);
				});

				initEditor();
			});
		</script>
		<script>

$.sceditor.plugins.bbcode.bbcode.set("size", {
    format: function($element, content) {
        var size = $element.css('font-size').replace('px', '');
        return '[size=' + size + ']' + content + '[/size]';
    },
    html: function(token, attrs, content) {
        return '<span style="font-size: ' + attrs.defaultattr + 'px">' + content + '</span>';
    }
});
		</script>
		<script>

    $(function(){$(function(){
      if ($("#text_editor_textarea").length < 1) return;
      $.sceditor.command.get('color')._menu  = function (editor, caller, callback) {
        editor.createDropDown(caller, 'color-picker', '<iframe id="colorFrame" src="/chatbox/chatbox_selectcolor.htm" style="height:165px;width:250px;border:none;"></iframe>');
        $('#colorFrame').load(function(){$('#colorFrame').contents().find('table[bgcolor="#000000"]').find('td').click(function(){callback($(this).attr('bgcolor'));editor.closeDropDown(true)})});
      }
    })});

	</script>