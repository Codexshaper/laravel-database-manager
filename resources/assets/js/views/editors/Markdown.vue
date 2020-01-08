<template>
	<div class="rich-editor">
		<markdown-editor :buttons="buttons" v-model="markdownContent"></markdown-editor>
	</div>
</template>

<script>
	export default {
		props: ["content"],
		name: 'mardown-code-editor',
        data(){
        	return {
        		markdownContent: this.content,
        		buttons: {

        		    'bold': {
        		        title: 'Bold',
        		        className: 'fas fa-bold',
        		        cmd: 'bold',
        		        hotkey: 'Ctrl-B'
        		    },

        		    'italic': {
        		        title: 'Italic',
        		        className: "fas fa-italic",
        		        cmd: 'italic',
        		        hotkey: 'Ctrl-I'
        		    },

        		    "strikethrough": {
        		        cmd: "strikethrough",
        		        className: "fas fa-strikethrough",
        		        title: "Strikethrough"
        		    },

        		    'heading': {
        		        title: 'Heading',
        		        className: "fas fa-heading",
        		        cmd: 'heading',
        		        hotkey: 'Ctrl-H'
        		    },

        		    'code': {
        		        title: 'Code',
        		        className: "fas fa-code",
        		        cmd: 'code',
        		        hotkey: 'Ctrl-X'
        		    },
        		    'quote': {
        		        title: 'Quote',
        		        className: "fas fa-quote-left",
        		        cmd: 'quote',
        		        hotkey: 'Ctrl-Q'
        		    },
        		    'link': {
        		        title: 'Link',
        		        className: "fas fa-link",
        		        cmd: 'link',
        		        hotkey: 'Ctrl-K'
        		    },
        		    'image': {
        		        title: 'Image',
        		        className: "fas fa-image",
        		        cmd: 'image',
        		        hotkey: 'Ctrl-P'
        		    },
        		    "fullscreen": {
        		        cmd: "fullscreen",
        		        className: "fas fa-arrows-alt no-disable no-mobile",
        		        title: "Toggle Fullscreen",
        		        hotkey: 'F11',
        		        ready: true
        		    },
        		    "preview": {
        		        cmd: "preview",
        		        className: "fas fa-eye no-disable",
        		        title: "Toggle Preview",
        		        hotkey: 'Ctrl-P',
        		        ready: true
        		    },

        		    "clipboard": {
        		        cmd: "clipboard",
        		        className: "fas fa-clipboard",
        		        title: "Copy & Markdown Format",
        		        hotkey: 'Ctrl-V'
        		    },

        		    "clean": {
        		        cmd: "clean",
        		        className: "fas fa-remove-format",
        		        title: "Clean html format"
        		    },

        		    "undo": {
        		        cmd: "undo",
        		        className: "fas fa-undo",
        		        title: "Undo",
        		        hotkey: 'Ctrl-Z'
        		    },

        		    "redo": {
        		        cmd: "redo",
        		        className: "fas fa-redo",
        		        title: "Redo",
        		        hotkey: 'Ctrl-Y'
        		    },

        		    "bullist": {
        		        cmd: "bullist",
        		        className: "fas fa-list-ul",
        		        title: "Generic List",

        		    },
        		    "numlist": {
        		        cmd: "numlist",
        		        className: "fas fa-list-ol",
        		        title: "Numbered List"
        		    }
        		}
        	};
        },

        watch: {
        	markdownContent(value) {
                this.$emit('input', value);
            },
        }
	}
</script>
