<template>
	<select class="form-control" v-model="field.type.name" @change="setOptionGroup(field, $event)" required="required">
		<option value="string">String</option>
        <option value="boolean">Boolean</option>
        <option value="double">Double</option>
        <option value="object">Object</option>
        <option value="arrayType">Array</option>
        <option value="binary">Binary data</option>
        <option value="undefined" disabled="disabled">Undefined</option>
        <option value="objectId">ObjectId</option>
        <option value="dateTime">Date</option>
        <option value="null">Null</option>
    	<option value="regex">Regular Expression</option>
        <option value="dbPointer" disabled="disabled">DBPointer</option>
        <option value="javascript">JavaScript</option>
    	<option value="symbol" disabled="disabled">Symbol</option>
    	<option value="javascriptWithScope">JavaScript (with scope)</option>
    	<option value="integer">32-bit integer</option>
    	<option value="timestamp">Timestamp</option>
    	<option value="longInteger">64-bit integer</option>
    	<option value="decimal">Decimal128</option>
    	<option value="minKey">Min key</option>
    	<option value="maxKey">Max key</option>
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