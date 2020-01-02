<template>
	<select class="form-control" v-model="field.type.name" @change="setOptionGroup(field, $event)" required="required">
		<optgroup label="Numbers">
		<option value="boolean">BOOLEAN</option>
		<option value="smallint">SMALLINT</option>
            <option value="integer">INTEGER</option>
            <option value="bigint">BIGINT</option>
            <option value="float">FLOAT</option>
            <option value="real">REAL</option>
            <option value="numeric">NUMERIC</option>
        </optgroup>
        <optgroup label="Strings">
            <option value="text">TEXT</option>
            <option value="varchar">VARCHAR</option>
            <option value="string">STRING</option>
            <option value="char">CHAR</option>
        </optgroup>
        <optgroup label="Date and Time">
        	<option value="date">DATE</option>
            <option value="datetime">DATETIME</option>
            <option value="time">TIME</option>
        </optgroup>
        <optgroup label="Binary">
        	<option value="blob">BLOB</option>
        </optgroup>
        <optgroup label="Lists">
        	<option value="json">JSON</option>
        </optgroup>
    </select>
</template>
<script>

	export default {
		props: {
			field: Object,
		},
		created() {
			// console.log(this.field);
		},
		methods: {
			setOptionGroup: function(field, event){
				const index = event.target.selectedIndex;
				const selectedOption = event.target.options[index];
				console.log( index );
				//if ( index > -1) {
		            const selectedOptgroup = selectedOption.parentElement;
		            // console.log( parent.attributes.label.nodeValue );
		            const selectedCategory = selectedOptgroup.getAttribute('label');
		            // console.log(selectedCategory);
		        //}
				// console.log( event.target.optiongroups );
				field.type.category = selectedCategory;
			}
		}
    }
</script>