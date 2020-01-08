<template>
	<select class="form-control" v-model="field.type.name" @change="setOptionGroup(field, $event)" required="required">
	   <option value="">Selected Filed Type</option>
	  <optgroup label="Numbers">
	    <option value="tinyint">TINYINT</option>
	    <option value="smallint">SMALLINT</option>
	    <option value="mediumint">MEDIUMINT</option>
	    <option value="integer">INTEGER</option>
	    <option value="bigint">BIGINT</option>
	    <option value="float">FLOAT</option>
	    <option value="double">DOUBLE</option>
	    <option value="decimal">DECIMAL</option>
	  </optgroup>
	  <optgroup label="Strings">
	    <option value="tinytext">TINYTEXT</option>
	    <option value="mediumtext">MEDIUMTEXT</option>
	    <option value="longtext">LONGTEXT</option>
	    <option value="text">TEXT</option>
	    <option value="varchar">VARCHAR</option>
	    <option value="char">CHAR</option>
	  </optgroup>
	  <optgroup label="Date and Time">
	    <option value="date">DATE</option>
	    <option value="datetime">DATETIME</option>
	    <option value="timestamp">TIMESTAMP</option>
	    <option value="time">TIME</option>
	    <option value="year">YEAR</option>
	  </optgroup>
	  <optgroup label="Binary">
	    <option value="longblob">LONGBLOB</option>
	    <option value="blob">BLOB</option>
	    <option value="mediumblob">MEDIUMBLOB</option>
	    <option value="tinyblob">TINYBLOB</option>
	    <option value="binary">BINARY</option>
	    <option value="varbinary">VARBINARY</option>
	    <option value="bit">BIT</option>
	  </optgroup>
	  <optgroup label="Lists">
	    <option disabled="disabled" value="set">SET</option>
	    <option disabled="disabled" value="enum">ENUM</option>
	    <option value="json">JSON</option>
	  </optgroup>
	  <optgroup label="Geometry">
	    <option value="geometrycollection">GEOMETRYCOLLECTION</option>
	    <option value="geometry">GEOMETRY</option>
	    <option value="linestring">LINESTRING</option>
	    <option value="multilinestring">MULTILINESTRING</option>
	    <option value="multipoint">MULTIPOINT</option>
	    <option value="multipolygon">MULTIPOLYGON</option>
	    <option value="point">POINT</option>
	    <option value="polygon">POLYGON</option>
	  </optgroup>
	</select>
</template>
<script>

	export default {
		props: {
			field: Object,
			notSupportIndexs: Array,
			notSupportDefault: Array
		},
		methods: {
			setOptionGroup: function(field, event){
				const index = event.target.selectedIndex;
				const selectedOption = event.target.options[index];
		        const selectedOptgroup = selectedOption.parentElement;
		        const selectedCategory = selectedOptgroup.getAttribute('label');
				field.type.category = selectedCategory;

				if(this.notSupportIndexs.includes(field.type.name)) {
				  this.field.index = "";
				}

				if(this.notSupportDefault.includes(field.type.name)) {
                	this.field.default = null;
              	}
			}
		}
    }
</script>