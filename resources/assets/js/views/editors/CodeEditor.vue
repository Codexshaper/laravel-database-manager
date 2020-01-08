<template>
	<div class="rich-editor">
		<editor-menu-bar v-if="isMenuBar" :editor="richEditor" v-slot="{ commands, isActive }">
		    <div class="rich-menu-bar">
		    	<ul class="editor-element">
		    		<li 
		    			v-for="(button, index) in buttons" 
		    			:key="index" 
		    			v-if="index != 'heading' && index != 'table'">
		    			<button 
		    				v-if="button.method != ''" 
		    				class="command" 
		    				:class="{ 'is-active': isActive[button.method](button.levels) }" 
		    				@click.prevent="command(commands, button)">
		    				<i :class="button.icon"></i>{{ button.text }}</button>
		    			 <button 
		    			 	v-else 
		    			 	class="command" 
		    			 	@click.prevent="command(commands, button)">
		    			 	<i :class="button.icon"></i>{{ button.text }}</button>
		    		</li>
		    	</ul>  
		    </div>
		</editor-menu-bar>
		<editor-content class="code-editor editor-content" :editor="richEditor"/>
	</div>
</template>

<script>
	import 'highlight.js/styles/github.css';

	import javascript from 'highlight.js/lib/languages/javascript'
	import css from 'highlight.js/lib/languages/css'
	import php from 'highlight.js/lib/languages/php'

	import { Editor, EditorContent, EditorMenuBar, EditorMenuBubble, EditorFloatingMenu } from 'tiptap'
	import { CustomCode } from './nodes/CustomCode'
	import {
	    History,
	    Blockquote,
	    CodeBlock,
	    CodeBlockHighlight,
	} from 'tiptap-extensions'

	export default {
		props: {
			name: {
				type: String,
				required: true
			},
			content: {
				type: String,
				default: "",
				required: false
			},
			options: {
				type: Object,
				default: function () {
        			return { 
        				isMenuBubble: true, 
        				isMenuBar: true, 
        			}
      			},
				required: false
			}
		},
		name: 'CodeEditor',
		components: {
            EditorMenuBar,
            EditorMenuBubble,
            EditorFloatingMenu,
            EditorContent,
        },
        data(){
        	return {
        		keepInBounds: true,
        		isMenuBubble: this.options.isMenuBubble,
        		isMenuBar: this.options.isMenuBar,
        		buttons:{
        			'codeBlock': {
        				class: '',
        				text: 'block',
        				icon: '',
        				commands: "code_block",
        				method: 'code_block',
        			},
        			'undo': {
        				class: '',
        				text: '',
        				icon: 'fas fa-undo-alt',
        				commands: "undo",
        				method: '',
        			},
        			'redo': {
        				class: '',
        				text: '',
        				icon: 'fas fa-redo',
        				commands: "redo",
        				method: '',
        			},

        		},
        		tableRows: 3,
        		tableColumns: 3,
        		isHeaderRow: 3,
        		richEditor: new Editor({
        		    extensions: [
        		        new CodeBlock(),
        		        new CodeBlockHighlight({
		                  languages: {
		                    javascript,
		                    css,
		                    php
		                  },
		                }),
        		        new CustomCode(),
        		        new History(),
        		    ],
        		    content: this.content,
        		    onUpdate: ( { state, getHTML, getJSON, transaction } ) => {
					    this.$emit('input', getHTML());
					}
        		}),
        	};
        },
        methods: {
        	command: function(commands, button){
        		if(button.commands == 'link') {
        			this.addLink(commands);
        		}else if(button.commands == 'image') {
        			this.addImage(commands);
        		}else {
        			commands[button.commands](button.params);
        		}
        	},
        	addLink: function(commands) {
        		var href = prompt("Enter your custom link", "");
        		if (href != null || href != "") {
        			commands.link({href: href});
        		}
        	},
        	addImage: function(commands) {
        		var src = prompt("Please enter image url", "");
        		if (src != null || src != "") {
        			commands.image({src: src});
        		}
        	},
        	createTable: function(commands, options = {}) {
        		if(options.length > 0) {
        			return commands.createTable({
				                rowsCount: options.tableRows, 
				                colsCount: options.tableColumns, 
				                withHeaderRow: options.isHeaderRow 
				            });
        		}
        		return commands.createTable({
			                rowsCount: this.tableRows, 
			                colsCount: this.tableColumns, 
			                withHeaderRow: this.isHeaderRow 
			            });
        	},
        	decodeAttrs(html) {
            	const textArea = document.createElement('textarea');
            	textArea.innerHTML = this.escapeQuote(html);
            	return this.deescapeQuote(textArea.innerText);
 
       		},
        	escapeQuote(html) {
                return html.replace(/&quot;/gi, '__$__');
            },
            deescapeQuote(html) {
                return html.replace(/__\$__/g, '&quot;');
            }
        },
        mounted(){
        	let content = `${this.content}`;
        	this.richEditor.setContent(content);
        },
        beforeDestroy() {
          this.richEditor.destroy()
        },
	}
</script>

<style scoped="scoped">
	
	.rich-editor .editor-content table {
		width: 100%;
	}
	.rich-editor .editor-content table th,
	.rich-editor .editor-content table td {
		border: 1px solid #ccc;
	}
	.code-editor pre {
		background-color: transparent;
	}
	.code-editor code {
		background: #ccc;
		color:#fff;
		font-size: .8rem;
		overflow-x: auto;
	}
	.editor-element {
		display: flex;
		flex-wrap: wrap;
	}
	.heading-lists,
	.table-elements{
		display: flex;
		flex-wrap: wrap;
	}
	.rich-menu-bar {
		background:#F5F5F5;
        padding: 10px;
        border: 1px solid #e1dddd;
        border-bottom: none;
	}		
	.rich-editor .command {
    	border: 1px solid #e1dddd;
		padding: 5px 17px;
		margin: 3px 5px;
		background: #fff;
    	border-radius: 5px;
	}
	.table-lists i, .heading-icon {
	    width: 47px;
		height: 36px;
		background:#fff;
		text-align: center;
		line-height: 36px;
		margin: 3px 5px;
		border: 1px solid#e1dddd;
		border-radius: 5px;
		cursor: pointer;
	}
	.editor-content {
	    background: #fff;
	    border: 1px solid #e1dddd;
	    padding: 21px;
	}
    .command.is-active{
    	 background: #1ca6d0;
    	 color: #fff;
    }
    .menububble{
	    position: absolute;
	    display: -webkit-box;
	    display: -ms-flexbox;
	    display: flex;
	    z-index: 20;
	    background: #1ca6d0;
	    border-radius: 5px;
	    padding: 2px;
	    margin-bottom: .5rem;
	    transform: translateX(-32%);
	    visibility: hidden;
	    opacity: 0;
	    -webkit-transition: opacity .2s,
	    visibility .2s;
	    transition: opacity .2s,
	    visibility .2s;
   }
    .menububble.is-active {
    	opacity: 1;
    	visibility: visible;
    }
    .menububble .rich-menu-bar {
	   background: none; 
	   padding: 0px; 
	   border:none; 
	   border-bottom: none;
   }
   .menububble  .command {
	    border: none;
	    padding: 3px 10px;
	    margin: 0px 5px;
	    background:none;
	    border-radius: 5px;
	    color:#fff;
	}
	.menububble  .command i{
		font-weight: 700;
	}
	.menububble .table-lists i, 
	.menububble .heading-icon {
	    width: 38px;
	    height: 29px;
	    background: none;
	    line-height: 29px;
	    margin: 0px 2px;
	    border:none;
	    border-radius: 5px;
	    cursor: pointer;
	    color:#fff;
	}
	.heading-lists, .table-elements {
	    display: flex;
	    flex-wrap: wrap;
	    border-top: 1px solid rgba(225,225,225,0.3);
	}

	/* =============== HighLight ===============*/

	.hljs-keyword,
	.hljs-selector-tag, 
	.hljs-subst{
		color: #51bcdd;
	}
	.hljs-title, .hljs-section, .hljs-selector-id {
	    color: #9acd32;;
	    font-weight: bold;
	}
	pre {
		&::before {
		content: attr(data-language);
		text-transform: uppercase;
		display: block;
		text-align: right;
		font-weight: bold;
		font-size: 0.6rem;
		}
		code {
			.hljs-comment,
			.hljs-quote {
			color: #999999;
			}
			.hljs-variable,
			.hljs-template-variable,
			.hljs-attribute,
			.hljs-tag,
			.hljs-name,
			.hljs-regexp,
			.hljs-link,
			.hljs-name,
			.hljs-selector-id,
			.hljs-selector-class {
			color: #f2777a;
			}
			.hljs-number,
			.hljs-meta,
			.hljs-built_in,
			.hljs-builtin-name,
			.hljs-literal,
			.hljs-type,
			.hljs-params {
			color: #f99157;
			}
			.hljs-string,
			.hljs-symbol,
			.hljs-bullet {
			color: #99cc99;
			}
			.hljs-title,
			.hljs-section {
			color: #ffcc66;
			}
			.hljs-keyword,
			.hljs-selector-tag {
				color: #6699cc;
				}
			.hljs-emphasis {
				font-style: italic;
			}
			.hljs-strong {
			font-weight: 700;
		  }
		}
	}

</style>