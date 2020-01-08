<template>
	<div class="rich-editor">
		<editor-menu-bar 
			v-if="isMenuBar" 
			:editor="richEditor" 
			v-slot="{ commands, isActive }">
		    <div class="rich-menu-bar">
		    	<ul class="editor-element">
		    		<li v-if="buttons.heading.length > 0">
		    			<i 
		    				class="fas fa-heading heading-icon" 
		    				data-toggle="collapse" 
		    				:data-target="'#'+name+'HeadingList'" 
		    				role="button"></i> 
		    		</li>
		    		<li v-if="buttons.table.length > 0" class="table-lists"> 
		    			<i 
		    				class="fas fa-table" 
		    				data-toggle="collapse" 
		    				:data-target="'#'+name+'TableList'" 
		    				role="button"></i>
		    		</li>
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

		    	<div class="collapse" :id="name+'HeadingList'">
    		    	<ul class="heading-lists">
    		    		<li  
    		    			v-for="(button, index) in buttons.heading" 
    		    			:key="index" 
    		    			class="heading-elements">
    		    			<button 
    		    				class="command" 
    		    				:class="{ 'is-active': isActive[button.method](button.levels) }" 
    		    				@click.prevent="command(commands, button)">
    		    				<i :class="button.icon"></i>{{ button.text }}</button>
    		    		</li>
    		    	</ul>
		    	</div>
		    	<div class="collapse" :id="name+'TableList'">
    		    	<ul class="table-elements">
    					<li  
    						v-for="(button, index) in buttons.table" 
    						:key="index">
    						<button 
    							class="command" 
    							:class="{ 'is-active': isActive[button.method](button.levels) }" 
    							@click.prevent="command(commands, button)">
    							<i :class="button.icon"></i>{{ button.text }}</button>
    					</li>
    				</ul>
		    	</div>  
		    </div>
		</editor-menu-bar>
		<editor-menu-bubble 
			v-if="isMenuBubble" 
			:editor="richEditor" 
			:keep-in-bounds="keepInBounds" 
			v-slot="{ commands, isActive, menu }">
		    <div
		        class="menububble"
		        :class="{ 'is-active': menu.isActive }"
		        :style="`left: ${menu.left}px; bottom: ${menu.bottom}px;`">
      		    <div class="rich-menu-bar">
      		    	<ul class="editor-element">
      		    		<li v-if="buttons.heading.length > 0">
      		    			<i 
      		    				class="fas fa-heading heading-icon" 
      		    				data-toggle="collapse" 
      		    				:data-target="'#'+name+'BubbleHeadingList'" 
      		    				role="button"></i> 
      		    		</li>
      		    		<li v-if="buttons.table.length > 0" class="table-lists"> 
      		    			<i 
      		    				class="fas fa-table" 
      		    				data-toggle="collapse" 
      		    				:data-target="'#'+name+'BubbleTableList'" 
      		    				role="button"></i>
      		    		</li>
      		    		<li 
      		    			v-for="(button, index) in buttons" 
      		    			:key="index" 
      		    			v-if="index != 'heading' && index != 'table' && button.bubble">
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

      		    	<div class="collapse" :id="name+'BubbleHeadingList'">
          		    	<ul class="heading-lists">
          		    		<li  
          		    			v-for="(button, index) in buttons.heading" 
          		    			:key="index" 
          		    			class="heading-elements">
          		    			<button 
          		    				class="command" 
          		    				:class="{ 'is-active': isActive[button.method](button.levels) }" 
          		    				@click.prevent="command(commands, button)">
          		    				<i :class="button.icon"></i>{{ button.text }}</button>
          		    		</li>
          		    	</ul>
      		    	</div>
      		    	<div class="collapse" :id="name+'BubbleTableList'">
          		    	<ul class="table-elements">
          					<li  
          						v-for="(button, index) in buttons.table" 
          						:key="index">
          						<button 
          							class="command" 
          							:class="{ 'is-active': isActive[button.method](button.levels) }" 
          							@click.prevent="command(commands, button)">
          							<i :class="button.icon"></i>{{ button.text }}</button>
          					</li>
          				</ul>
      		    	</div>  
      		    </div>
		    </div>
		  </editor-menu-bubble>
		<editor-content class="editor-content" :editor="richEditor"/>
	</div>
</template>

<script>
	import 'highlight.js/styles/github.css';

	import javascript from 'highlight.js/lib/languages/javascript'
	import css from 'highlight.js/lib/languages/css'
	import php from 'highlight.js/lib/languages/php'

	import { Editor, EditorContent, EditorMenuBar, EditorMenuBubble, EditorFloatingMenu } from 'tiptap'
	import {
	    Blockquote,
	    CodeBlock,
	    CodeBlockHighlight,
	    HardBreak,
	    Heading,
	    OrderedList,
	    BulletList,
	    ListItem,
	    TodoItem,
	    TodoList,
	    Bold,
	    Code,
	    Italic,
	    Link,
	    Strike,
	    Underline,
	    History,
	    Table, 
	    TableCell, 
	    TableHeader, 
	    TableRow,
	    Image 
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
		name: 'RichEditor',
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
        			'blockquote': {
        				class: '',
        				icon: 'fas fa-quote-left',
        				commands: 'blockquote',
        				method: 'blockquote',
        				bubble: true
        			},
	    			 heading:[
	    			 	{
	        				class: '',
	        				text: 'H1',
	        				icon: '',
	        				commands: "heading",
	        				method: 'heading',
	        				params: {level: 1}
	        			},
	        			{
	        				class: '',
	        				text: 'H2',
	        				icon: '',
	        				commands: "heading",
	        				method: 'heading',
	        				params: {level: 2}
	        			},
	        			{
	        				class: '',
	        				text: 'H3',
	        				icon: '',
	        				commands: "heading",
	        				method: 'heading',
	        				params: {level: 3}
	        			},
	        			{
	        				class: '',
	        				text: 'H4',
	        				icon: '',
	        				commands: "heading",
	        				method: 'heading',
	        				params: {level: 4}
	        			},
	        			{
	        				class: '',
	        				text: 'H5',
	        				icon: '',
	        				commands: "heading",
	        				method: 'heading',
	        				params: {level: 5}
	        			},
	        			{
	        				class: '',
	        				text: 'H6',
	        				icon: '',
	        				commands: "heading",
	        				method: 'heading',
	        				params: {level: 6}
	        			},
	    			 ],
        			
        			'image': {
        				class: '',
        				text: '',
        				icon: 'fas fa-image',
        				commands: "image",
        				method: 'image',
        			},
        			'bold': {
        				class: '',
        				text: '',
        				icon: 'fas fa-bold',
        				commands: "bold",
        				method: 'bold',
        				bubble: true
        			},
        			'ordered_list': {
        				class: '',
        				text: '',
        				icon: 'fas fa-list-ol',
        				commands: "ordered_list",
        				method: 'ordered_list',
        			},
        			'bullet_list': {
        				class: '',
        				text: '',
        				icon: 'fas fa-list-ul',
        				commands: "bullet_list",
        				method: 'bullet_list',
        			},
        			'todo_list': {
        				class: '',
        				text: 'todo_list',
        				icon: '',
        				commands: "todo_list",
        				method: 'todo_list',
        			},
        			'code': {
        				class: '',
        				text: '',
        				icon: 'fas fa-code',
        				commands: "code",
        				method: 'code',
        				bubble: true
        			},
        			'codeBlock': {
        				class: '',
        				text: 'block',
        				icon: '',
        				commands: "code_block",
        				method: 'code_block',
        			},
        			'italic': {
        				class: '',
        				text: '',
        				icon: 'fas fa-italic',
        				commands: "italic",
        				method: 'italic',
        				bubble: true
        			},
        			'link': {
        				class: '',
        				text: '',
        				icon: 'fas fa-link',
        				commands: "link",
        				method: 'link',
        				bubble: true
        			},
        			'strike': {
        				class: '',
        				text: '',
        				icon: 'fas fa-strikethrough',
        				commands: "strike",
        				method: 'strike',
        				bubble: true
        			},
        			'underline': {
        				class: '',
        				text: '',
        				icon: 'fas fa-underline',
        				commands: "underline",
        				method: 'underline',
        				bubble: true
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
        			table:[
        				{
	        				class: '',
	        				text: 'createTable',
	        				icon: '',
	        				commands: "createTable",
	        				method: 'bold',
	        				params: {
				                rowsCount: 3, 
				                colsCount: 3, 
				                withHeaderRow: false 
					        }
	        			},
	        			{
	        				class: '',
	        				text: 'deleteTable',
	        				icon: '',
	        				commands: "deleteTable",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'addColumnBefore',
	        				icon: '',
	        				commands: "addColumnBefore",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'addColumnAfter',
	        				icon: '',
	        				commands: "addColumnAfter",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'deleteColumn',
	        				icon: '',
	        				commands: "deleteColumn",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'deleteRow',
	        				icon: '',
	        				commands: "deleteRow",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'addRowBefore',
	        				icon: '',
	        				commands: "addRowBefore",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'addRowAfter',
	        				icon: '',
	        				commands: "addRowAfter",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'toggleCellMerge',
	        				icon: '',
	        				commands: "toggleCellMerge",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'mergeCells',
	        				icon: '',
	        				commands: "mergeCells",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'splitCell',
	        				icon: '',
	        				commands: "splitCell",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'toggleHeaderColumn',
	        				icon: '',
	        				commands: "toggleHeaderColumn",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'toggleHeaderRow',
	        				icon: '',
	        				commands: "toggleHeaderRow",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'toggleHeaderCell',
	        				icon: '',
	        				commands: "toggleHeaderCell",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'setCellAttr',
	        				icon: '',
	        				commands: "setCellAttr",
	        				method: 'bold',
	        			},
	        			{
	        				class: '',
	        				text: 'fixTables',
	        				icon: '',
	        				commands: "fixTables",
	        				method: 'bold',
	        			},
        			],

        		},
        		tableRows: 3,
        		tableColumns: 3,
        		isHeaderRow: 3,
        		richEditor: new Editor({
        		    extensions: [
        		        new Blockquote(),
        		        new CodeBlock(),
        		        new CodeBlockHighlight({
		                  languages: {
		                    javascript,
		                    css,
		                    php
		                  },
		                }),
        		        new HardBreak(),
        		        new Heading({ levels: [1, 2, 3,4,5,6] }),
        		        new BulletList(),
        		        new OrderedList(),
        		        new ListItem(),
        		        new TodoItem(),
        		        new TodoList(),
        		        new Bold(),
        		        new Code(),
        		        new Italic(),
        		        new Link(),
        		        new Strike(),
        		        new Underline(),
        		        new History(),
        		        new Table(),
        		        new TableCell(),
        		        new TableHeader(),
        		        new TableRow(),
        		        new Image(),
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

<style>
	
	.rich-editor .editor-content table {
		width: 100%;
	}
	.rich-editor .editor-content table th,
	.rich-editor .editor-content table td {
		border: 1px solid #ccc;
	}
	.editor-content pre {
		padding: .7rem 1rem;
		border-radius: 5px;
		background: #2c353e;
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
	.rich-editor .command,
	.table-lists i, .heading-icon,
	.command.is-active{
		box-shadow: 0px 2px 3px rgba(0,0,0,.06);
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