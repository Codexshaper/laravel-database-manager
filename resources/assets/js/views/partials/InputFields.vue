<template>
	<select class="form-control" v-model="field.type">
	   <option value="">Select Type</option>
	   <option value="auto_increment"> Auto Increment</option>
	   <option value="checkbox">  Checkbox</option>
	   <option value="multiple_checkbox"> Multiple Checkbox</option>
	   <option value="color"> Color</option>                         
	   <option value="date"> Date</option>                         
	   <option value="email"> Email</option>                         
	   <option value="file"> File</option>                         
	   <option value="image"> Image</option>                         
	   <option value="multiple_images"> Multiple Images</option>                         
	   <option value="media_picker"> Media Picker</option>                         
	   <option value="number">Number</option>                         
	   <option value="password">Password</option>                         
	   <option value="radio"> Radio Button</option>                         
	   <option value="rich_text"> Rich Text</option>                         
	   <option value="code_editor">Code Editor</option>                         
	   <option value="markdown_editor"> Markdown Editor</option>                         
	   <option value="dropdown"> Dropdown</option>                         
	   <option value="multiple_dropdown"> Multiple Dropdown</option>     
	   <option value="text"> Text</option>   
	   <option value="unique"> Unique Text</option>  
	   <option value="unique_number"> Unique Number</option>                    
	   <option value="textarea"> Text Area</option>                         
	   <option value="timestamp"> Timestamp</option>                         
	   <option value="hidden"> Hidden</option>                         
	   <option value="coordinates"> Coordinates</option>                         
	</select>
</template>
<script>

	export default {
		props: {
			field: Object,
		},
    }
</script>