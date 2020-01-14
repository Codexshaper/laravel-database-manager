<template>
	<select v-model="table.collation" @change="setOptionGroup(table, $event)" required="required" class="form-control">
		<option value="">Collation</option>
		<option value=""></option>
		<optgroup label="armscii8" title="ARMSCII-8 Armenian">
			<option value="armscii8_bin" title="Armenian, binary">armscii8_bin</option>
			<option value="armscii8_general_ci" title="Armenian, case-insensitive">armscii8_general_ci</option>
		</optgroup>
		<optgroup label="ascii" title="US ASCII">
			<option value="ascii_bin" title="West European, binary">ascii_bin</option>
			<option value="ascii_general_ci" title="West European, case-insensitive">ascii_general_ci</option>
		</optgroup>
		<optgroup label="big5" title="Big5 Traditional Chinese">
			<option value="big5_bin" title="Traditional Chinese, binary">big5_bin</option>
			<option value="big5_chinese_ci" title="Traditional Chinese, case-insensitive">big5_chinese_ci</option>
		</optgroup>
		<optgroup label="binary" title="Binary pseudo charset">
			<option value="binary" title="Binary">binary</option>
		</optgroup>
		<optgroup label="cp1250" title="Windows Central European">
			<option value="cp1250_bin" title="Central European, binary">cp1250_bin</option>
			<option value="cp1250_croatian_ci" title="Croatian, case-insensitive">cp1250_croatian_ci</option>
			<option value="cp1250_czech_cs" title="Czech, case-sensitive">cp1250_czech_cs</option>
			<option value="cp1250_general_ci" title="Central European, case-insensitive">cp1250_general_ci</option>
			<option value="cp1250_polish_ci" title="Polish, case-insensitive">cp1250_polish_ci</option>
		</optgroup>
		<optgroup label="cp1251" title="Windows Cyrillic">
			<option value="cp1251_bin" title="Cyrillic, binary">cp1251_bin</option>
			<option value="cp1251_bulgarian_ci" title="Bulgarian, case-insensitive">cp1251_bulgarian_ci</option>
			<option value="cp1251_general_ci" title="Cyrillic, case-insensitive">cp1251_general_ci</option>
			<option value="cp1251_general_cs" title="Cyrillic, case-sensitive">cp1251_general_cs</option>
			<option value="cp1251_ukrainian_ci" title="Ukrainian, case-insensitive">cp1251_ukrainian_ci</option>
		</optgroup>
		<optgroup label="cp1256" title="Windows Arabic">
			<option value="cp1256_bin" title="Arabic, binary">cp1256_bin</option>
			<option value="cp1256_general_ci" title="Arabic, case-insensitive">cp1256_general_ci</option>
		</optgroup>
		<optgroup label="cp1257" title="Windows Baltic">
			<option value="cp1257_bin" title="Baltic, binary">cp1257_bin</option>
			<option value="cp1257_general_ci" title="Baltic, case-insensitive">cp1257_general_ci</option>
			<option value="cp1257_lithuanian_ci" title="Lithuanian, case-insensitive">cp1257_lithuanian_ci</option>
		</optgroup>
		<optgroup label="cp850" title="DOS West European">
			<option value="cp850_bin" title="West European, binary">cp850_bin</option>
			<option value="cp850_general_ci" title="West European, case-insensitive">cp850_general_ci</option>
		</optgroup>
		<optgroup label="cp852" title="DOS Central European">
			<option value="cp852_bin" title="Central European, binary">cp852_bin</option>
			<option value="cp852_general_ci" title="Central European, case-insensitive">cp852_general_ci</option>
		</optgroup>
		<optgroup label="cp866" title="DOS Russian">
			<option value="cp866_bin" title="Russian, binary">cp866_bin</option>
			<option value="cp866_general_ci" title="Russian, case-insensitive">cp866_general_ci</option>
		</optgroup>
		<optgroup label="cp932" title="SJIS for Windows Japanese">
			<option value="cp932_bin" title="Japanese, binary">cp932_bin</option>
			<option value="cp932_japanese_ci" title="Japanese, case-insensitive">cp932_japanese_ci</option>
		</optgroup>
		<optgroup label="dec8" title="DEC West European">
			<option value="dec8_bin" title="West European, binary">dec8_bin</option>
			<option value="dec8_swedish_ci" title="Swedish, case-insensitive">dec8_swedish_ci</option>
		</optgroup>
		<optgroup label="eucjpms" title="UJIS for Windows Japanese">
			<option value="eucjpms_bin" title="Japanese, binary">eucjpms_bin</option>
			<option value="eucjpms_japanese_ci" title="Japanese, case-insensitive">eucjpms_japanese_ci</option>
		</optgroup>
		<optgroup label="euckr" title="EUC-KR Korean">
			<option value="euckr_bin" title="Korean, binary">euckr_bin</option>
			<option value="euckr_korean_ci" title="Korean, case-insensitive">euckr_korean_ci</option>
		</optgroup>
		<optgroup label="gb2312" title="GB2312 Simplified Chinese">
			<option value="gb2312_bin" title="Simplified Chinese, binary">gb2312_bin</option>
			<option value="gb2312_chinese_ci" title="Simplified Chinese, case-insensitive">gb2312_chinese_ci</option>
		</optgroup>
		<optgroup label="gbk" title="GBK Simplified Chinese">
			<option value="gbk_bin" title="Simplified Chinese, binary">gbk_bin</option>
			<option value="gbk_chinese_ci" title="Simplified Chinese, case-insensitive">gbk_chinese_ci</option>
		</optgroup>
		<optgroup label="geostd8" title="GEOSTD8 Georgian">
			<option value="geostd8_bin" title="Georgian, binary">geostd8_bin</option>
			<option value="geostd8_general_ci" title="Georgian, case-insensitive">geostd8_general_ci</option>
		</optgroup>
		<optgroup label="greek" title="ISO 8859-7 Greek">
			<option value="greek_bin" title="Greek, binary">greek_bin</option>
			<option value="greek_general_ci" title="Greek, case-insensitive">greek_general_ci</option>
		</optgroup>
		<optgroup label="hebrew" title="ISO 8859-8 Hebrew">
			<option value="hebrew_bin" title="Hebrew, binary">hebrew_bin</option>
			<option value="hebrew_general_ci" title="Hebrew, case-insensitive">hebrew_general_ci</option>
		</optgroup>
		<optgroup label="hp8" title="HP West European">
			<option value="hp8_bin" title="West European, binary">hp8_bin</option>
			<option value="hp8_english_ci" title="English, case-insensitive">hp8_english_ci</option>
		</optgroup>
		<optgroup label="keybcs2" title="DOS Kamenicky Czech-Slovak">
			<option value="keybcs2_bin" title="Czech-Slovak, binary">keybcs2_bin</option>
			<option value="keybcs2_general_ci" title="Czech-Slovak, case-insensitive">keybcs2_general_ci</option>
		</optgroup>
		<optgroup label="koi8r" title="KOI8-R Relcom Russian">
			<option value="koi8r_bin" title="Russian, binary">koi8r_bin</option>
			<option value="koi8r_general_ci" title="Russian, case-insensitive">koi8r_general_ci</option>
		</optgroup>
		<optgroup label="koi8u" title="KOI8-U Ukrainian">
			<option value="koi8u_bin" title="Ukrainian, binary">koi8u_bin</option>
			<option value="koi8u_general_ci" title="Ukrainian, case-insensitive">koi8u_general_ci</option>
		</optgroup>
		<optgroup label="latin1" title="cp1252 West European">
			<option value="latin1_bin" title="West European, binary">latin1_bin</option>
			<option value="latin1_danish_ci" title="Danish, case-insensitive">latin1_danish_ci</option>
			<option value="latin1_general_ci" title="West European, case-insensitive">latin1_general_ci</option>
			<option value="latin1_general_cs" title="West European, case-sensitive">latin1_general_cs</option>
			<option value="latin1_german1_ci" title="German (dictionary order), case-insensitive">latin1_german1_ci</option>
			<option value="latin1_german2_ci" title="German (phone book order), case-insensitive">latin1_german2_ci</option>
			<option value="latin1_spanish_ci" title="Spanish (modern), case-insensitive">latin1_spanish_ci</option>
			<option value="latin1_swedish_ci" title="Swedish, case-insensitive">latin1_swedish_ci</option>
		</optgroup>
		<optgroup label="latin2" title="ISO 8859-2 Central European">
			<option value="latin2_bin" title="Central European, binary">latin2_bin</option>
			<option value="latin2_croatian_ci" title="Croatian, case-insensitive">latin2_croatian_ci</option>
			<option value="latin2_czech_cs" title="Czech, case-sensitive">latin2_czech_cs</option>
			<option value="latin2_general_ci" title="Central European, case-insensitive">latin2_general_ci</option>
			<option value="latin2_hungarian_ci" title="Hungarian, case-insensitive">latin2_hungarian_ci</option>
		</optgroup>
		<optgroup label="latin5" title="ISO 8859-9 Turkish">
			<option value="latin5_bin" title="Turkish, binary">latin5_bin</option>
			<option value="latin5_turkish_ci" title="Turkish, case-insensitive">latin5_turkish_ci</option>
		</optgroup>
		<optgroup label="latin7" title="ISO 8859-13 Baltic">
			<option value="latin7_bin" title="Baltic, binary">latin7_bin</option>
			<option value="latin7_estonian_cs" title="Estonian, case-sensitive">latin7_estonian_cs</option>
			<option value="latin7_general_ci" title="Baltic, case-insensitive">latin7_general_ci</option>
			<option value="latin7_general_cs" title="Baltic, case-sensitive">latin7_general_cs</option>
		</optgroup>
		<optgroup label="macce" title="Mac Central European">
			<option value="macce_bin" title="Central European, binary">macce_bin</option>
			<option value="macce_general_ci" title="Central European, case-insensitive">macce_general_ci</option>
		</optgroup>
		<optgroup label="macroman" title="Mac West European">
			<option value="macroman_bin" title="West European, binary">macroman_bin</option>
			<option value="macroman_general_ci" title="West European, case-insensitive">macroman_general_ci</option>
		</optgroup>
		<optgroup label="sjis" title="Shift-JIS Japanese">
			<option value="sjis_bin" title="Japanese, binary">sjis_bin</option>
			<option value="sjis_japanese_ci" title="Japanese, case-insensitive">sjis_japanese_ci</option>
		</optgroup>
		<optgroup label="swe7" title="7bit Swedish">
			<option value="swe7_bin" title="Swedish, binary">swe7_bin</option>
			<option value="swe7_swedish_ci" title="Swedish, case-insensitive">swe7_swedish_ci</option>
		</optgroup>
		<optgroup label="tis620" title="TIS620 Thai">
			<option value="tis620_bin" title="Thai, binary">tis620_bin</option>
			<option value="tis620_thai_ci" title="Thai, case-insensitive">tis620_thai_ci</option>
		</optgroup>
		<optgroup label="ucs2" title="UCS-2 Unicode">
			<option value="ucs2_bin" title="Unicode, binary">ucs2_bin</option>
			<option value="ucs2_croatian_ci" title="Croatian, case-insensitive">ucs2_croatian_ci</option>
			<option value="ucs2_croatian_mysql561_ci" title="Croatian (MySQL 5.6.1), case-insensitive">ucs2_croatian_mysql561_ci</option>
			<option value="ucs2_czech_ci" title="Czech, case-insensitive">ucs2_czech_ci</option>
			<option value="ucs2_danish_ci" title="Danish, case-insensitive">ucs2_danish_ci</option>
			<option value="ucs2_esperanto_ci" title="Esperanto, case-insensitive">ucs2_esperanto_ci</option>
			<option value="ucs2_estonian_ci" title="Estonian, case-insensitive">ucs2_estonian_ci</option>
			<option value="ucs2_general_ci" title="Unicode, case-insensitive">ucs2_general_ci</option>
			<option value="ucs2_general_mysql500_ci" title="Unicode (MySQL 5.0.0), case-insensitive">ucs2_general_mysql500_ci</option>
			<option value="ucs2_german2_ci" title="German (phone book order), case-insensitive">ucs2_german2_ci</option>
			<option value="ucs2_hungarian_ci" title="Hungarian, case-insensitive">ucs2_hungarian_ci</option>
			<option value="ucs2_icelandic_ci" title="Icelandic, case-insensitive">ucs2_icelandic_ci</option>
			<option value="ucs2_latvian_ci" title="Latvian, case-insensitive">ucs2_latvian_ci</option>
			<option value="ucs2_lithuanian_ci" title="Lithuanian, case-insensitive">ucs2_lithuanian_ci</option>
			<option value="ucs2_myanmar_ci" title="Burmese, case-insensitive">ucs2_myanmar_ci</option>
			<option value="ucs2_persian_ci" title="Persian, case-insensitive">ucs2_persian_ci</option>
			<option value="ucs2_polish_ci" title="Polish, case-insensitive">ucs2_polish_ci</option>
			<option value="ucs2_roman_ci" title="West European, case-insensitive">ucs2_roman_ci</option>
			<option value="ucs2_romanian_ci" title="Romanian, case-insensitive">ucs2_romanian_ci</option>
			<option value="ucs2_sinhala_ci" title="Sinhalese, case-insensitive">ucs2_sinhala_ci</option>
			<option value="ucs2_slovak_ci" title="Slovak, case-insensitive">ucs2_slovak_ci</option>
			<option value="ucs2_slovenian_ci" title="Slovenian, case-insensitive">ucs2_slovenian_ci</option>
			<option value="ucs2_spanish2_ci" title="Spanish (traditional), case-insensitive">ucs2_spanish2_ci</option>
			<option value="ucs2_spanish_ci" title="Spanish (modern), case-insensitive">ucs2_spanish_ci</option>
			<option value="ucs2_swedish_ci" title="Swedish, case-insensitive">ucs2_swedish_ci</option>
			<option value="ucs2_thai_520_w2" title="Thai (UCA 5.2.0), multi-level">ucs2_thai_520_w2</option>
			<option value="ucs2_turkish_ci" title="Turkish, case-insensitive">ucs2_turkish_ci</option>
			<option value="ucs2_unicode_520_ci" title="Unicode (UCA 5.2.0), case-insensitive">ucs2_unicode_520_ci</option>
			<option value="ucs2_unicode_ci" title="Unicode, case-insensitive">ucs2_unicode_ci</option>
			<option value="ucs2_vietnamese_ci" title="Vietnamese, case-insensitive">ucs2_vietnamese_ci</option>
		</optgroup>
		<optgroup label="ujis" title="EUC-JP Japanese">
			<option value="ujis_bin" title="Japanese, binary">ujis_bin</option>
			<option value="ujis_japanese_ci" title="Japanese, case-insensitive">ujis_japanese_ci</option>
		</optgroup>
		<optgroup label="utf16" title="UTF-16 Unicode">
			<option value="utf16_bin" title="Unicode, binary">utf16_bin</option>
			<option value="utf16_croatian_ci" title="Croatian, case-insensitive">utf16_croatian_ci</option>
			<option value="utf16_croatian_mysql561_ci" title="Croatian (MySQL 5.6.1), case-insensitive">utf16_croatian_mysql561_ci</option>
			<option value="utf16_czech_ci" title="Czech, case-insensitive">utf16_czech_ci</option>
			<option value="utf16_danish_ci" title="Danish, case-insensitive">utf16_danish_ci</option>
			<option value="utf16_esperanto_ci" title="Esperanto, case-insensitive">utf16_esperanto_ci</option>
			<option value="utf16_estonian_ci" title="Estonian, case-insensitive">utf16_estonian_ci</option>
			<option value="utf16_general_ci" title="Unicode, case-insensitive">utf16_general_ci</option>
			<option value="utf16_german2_ci" title="German (phone book order), case-insensitive">utf16_german2_ci</option>
			<option value="utf16_hungarian_ci" title="Hungarian, case-insensitive">utf16_hungarian_ci</option>
			<option value="utf16_icelandic_ci" title="Icelandic, case-insensitive">utf16_icelandic_ci</option>
			<option value="utf16_latvian_ci" title="Latvian, case-insensitive">utf16_latvian_ci</option>
			<option value="utf16_lithuanian_ci" title="Lithuanian, case-insensitive">utf16_lithuanian_ci</option>
			<option value="utf16_myanmar_ci" title="Burmese, case-insensitive">utf16_myanmar_ci</option>
			<option value="utf16_persian_ci" title="Persian, case-insensitive">utf16_persian_ci</option>
			<option value="utf16_polish_ci" title="Polish, case-insensitive">utf16_polish_ci</option>
			<option value="utf16_roman_ci" title="West European, case-insensitive">utf16_roman_ci</option>
			<option value="utf16_romanian_ci" title="Romanian, case-insensitive">utf16_romanian_ci</option>
			<option value="utf16_sinhala_ci" title="Sinhalese, case-insensitive">utf16_sinhala_ci</option>
			<option value="utf16_slovak_ci" title="Slovak, case-insensitive">utf16_slovak_ci</option>
			<option value="utf16_slovenian_ci" title="Slovenian, case-insensitive">utf16_slovenian_ci</option>
			<option value="utf16_spanish2_ci" title="Spanish (traditional), case-insensitive">utf16_spanish2_ci</option>
			<option value="utf16_spanish_ci" title="Spanish (modern), case-insensitive">utf16_spanish_ci</option>
			<option value="utf16_swedish_ci" title="Swedish, case-insensitive">utf16_swedish_ci</option>
			<option value="utf16_thai_520_w2" title="Thai (UCA 5.2.0), multi-level">utf16_thai_520_w2</option>
			<option value="utf16_turkish_ci" title="Turkish, case-insensitive">utf16_turkish_ci</option>
			<option value="utf16_unicode_520_ci" title="Unicode (UCA 5.2.0), case-insensitive">utf16_unicode_520_ci</option>
			<option value="utf16_unicode_ci" title="Unicode, case-insensitive">utf16_unicode_ci</option>
			<option value="utf16_vietnamese_ci" title="Vietnamese, case-insensitive">utf16_vietnamese_ci</option>
		</optgroup>
		<optgroup label="utf16le" title="UTF-16LE Unicode">
			<option value="utf16le_bin" title="Unicode, binary">utf16le_bin</option>
			<option value="utf16le_general_ci" title="Unicode, case-insensitive">utf16le_general_ci</option>
		</optgroup>
		<optgroup label="utf32" title="UTF-32 Unicode">
			<option value="utf32_bin" title="Unicode, binary">utf32_bin</option>
			<option value="utf32_croatian_ci" title="Croatian, case-insensitive">utf32_croatian_ci</option>
			<option value="utf32_croatian_mysql561_ci" title="Croatian (MySQL 5.6.1), case-insensitive">utf32_croatian_mysql561_ci</option>
			<option value="utf32_czech_ci" title="Czech, case-insensitive">utf32_czech_ci</option>
			<option value="utf32_danish_ci" title="Danish, case-insensitive">utf32_danish_ci</option>
			<option value="utf32_esperanto_ci" title="Esperanto, case-insensitive">utf32_esperanto_ci</option>
			<option value="utf32_estonian_ci" title="Estonian, case-insensitive">utf32_estonian_ci</option>
			<option value="utf32_general_ci" title="Unicode, case-insensitive">utf32_general_ci</option>
			<option value="utf32_german2_ci" title="German (phone book order), case-insensitive">utf32_german2_ci</option>
			<option value="utf32_hungarian_ci" title="Hungarian, case-insensitive">utf32_hungarian_ci</option>
			<option value="utf32_icelandic_ci" title="Icelandic, case-insensitive">utf32_icelandic_ci</option>
			<option value="utf32_latvian_ci" title="Latvian, case-insensitive">utf32_latvian_ci</option>
			<option value="utf32_lithuanian_ci" title="Lithuanian, case-insensitive">utf32_lithuanian_ci</option>
			<option value="utf32_myanmar_ci" title="Burmese, case-insensitive">utf32_myanmar_ci</option>
			<option value="utf32_persian_ci" title="Persian, case-insensitive">utf32_persian_ci</option>
			<option value="utf32_polish_ci" title="Polish, case-insensitive">utf32_polish_ci</option>
			<option value="utf32_roman_ci" title="West European, case-insensitive">utf32_roman_ci</option>
			<option value="utf32_romanian_ci" title="Romanian, case-insensitive">utf32_romanian_ci</option>
			<option value="utf32_sinhala_ci" title="Sinhalese, case-insensitive">utf32_sinhala_ci</option>
			<option value="utf32_slovak_ci" title="Slovak, case-insensitive">utf32_slovak_ci</option>
			<option value="utf32_slovenian_ci" title="Slovenian, case-insensitive">utf32_slovenian_ci</option>
			<option value="utf32_spanish2_ci" title="Spanish (traditional), case-insensitive">utf32_spanish2_ci</option>
			<option value="utf32_spanish_ci" title="Spanish (modern), case-insensitive">utf32_spanish_ci</option>
			<option value="utf32_swedish_ci" title="Swedish, case-insensitive">utf32_swedish_ci</option>
			<option value="utf32_thai_520_w2" title="Thai (UCA 5.2.0), multi-level">utf32_thai_520_w2</option>
			<option value="utf32_turkish_ci" title="Turkish, case-insensitive">utf32_turkish_ci</option>
			<option value="utf32_unicode_520_ci" title="Unicode (UCA 5.2.0), case-insensitive">utf32_unicode_520_ci</option>
		<option value="utf32_unicode_ci" title="Unicode, case-insensitive">utf32_unicode_ci</option>
		<option value="utf32_vietnamese_ci" title="Vietnamese, case-insensitive">utf32_vietnamese_ci</option>
		</optgroup>
		<optgroup label="utf8" title="UTF-8 Unicode">
			<option value="utf8_bin" title="Unicode, binary">utf8_bin</option>
			<option value="utf8_croatian_ci" title="Croatian, case-insensitive">utf8_croatian_ci</option>
			<option value="utf8_croatian_mysql561_ci" title="Croatian (MySQL 5.6.1), case-insensitive">utf8_croatian_mysql561_ci</option>
			<option value="utf8_czech_ci" title="Czech, case-insensitive">utf8_czech_ci</option>
			<option value="utf8_danish_ci" title="Danish, case-insensitive">utf8_danish_ci</option>
			<option value="utf8_esperanto_ci" title="Esperanto, case-insensitive">utf8_esperanto_ci</option>
			<option value="utf8_estonian_ci" title="Estonian, case-insensitive">utf8_estonian_ci</option>
			<option value="utf8_general_ci" title="Unicode, case-insensitive">utf8_general_ci</option>
			<option value="utf8_general_mysql500_ci" title="Unicode (MySQL 5.0.0), case-insensitive">utf8_general_mysql500_ci</option>
			<option value="utf8_german2_ci" title="German (phone book order), case-insensitive">utf8_german2_ci</option>
			<option value="utf8_hungarian_ci" title="Hungarian, case-insensitive">utf8_hungarian_ci</option>
			<option value="utf8_icelandic_ci" title="Icelandic, case-insensitive">utf8_icelandic_ci</option>
			<option value="utf8_latvian_ci" title="Latvian, case-insensitive">utf8_latvian_ci</option>
			<option value="utf8_lithuanian_ci" title="Lithuanian, case-insensitive">utf8_lithuanian_ci</option>
			<option value="utf8_myanmar_ci" title="Burmese, case-insensitive">utf8_myanmar_ci</option>
			<option value="utf8_persian_ci" title="Persian, case-insensitive">utf8_persian_ci</option>
			<option value="utf8_polish_ci" title="Polish, case-insensitive">utf8_polish_ci</option>
			<option value="utf8_roman_ci" title="West European, case-insensitive">utf8_roman_ci</option>
			<option value="utf8_romanian_ci" title="Romanian, case-insensitive">utf8_romanian_ci</option>
			<option value="utf8_sinhala_ci" title="Sinhalese, case-insensitive">utf8_sinhala_ci</option>
			<option value="utf8_slovak_ci" title="Slovak, case-insensitive">utf8_slovak_ci</option>
			<option value="utf8_slovenian_ci" title="Slovenian, case-insensitive">utf8_slovenian_ci</option>
			<option value="utf8_spanish2_ci" title="Spanish (traditional), case-insensitive">utf8_spanish2_ci</option>
			<option value="utf8_spanish_ci" title="Spanish (modern), case-insensitive">utf8_spanish_ci</option>
			<option value="utf8_swedish_ci" title="Swedish, case-insensitive">utf8_swedish_ci</option>
			<option value="utf8_thai_520_w2" title="Thai (UCA 5.2.0), multi-level">utf8_thai_520_w2</option>
			<option value="utf8_turkish_ci" title="Turkish, case-insensitive">utf8_turkish_ci</option>
			<option value="utf8_unicode_520_ci" title="Unicode (UCA 5.2.0), case-insensitive">utf8_unicode_520_ci</option>
			<option value="utf8_unicode_ci" title="Unicode, case-insensitive">utf8_unicode_ci</option>
			<option value="utf8_vietnamese_ci" title="Vietnamese, case-insensitive">utf8_vietnamese_ci</option>
		</optgroup>
		<optgroup label="utf8mb4" title="UTF-8 Unicode">
			<option value="utf8mb4_bin" title="Unicode (UCA 4.0.0), binary">utf8mb4_bin</option>
			<option value="utf8mb4_croatian_ci" title="Croatian (UCA 4.0.0), case-insensitive">utf8mb4_croatian_ci</option>
			<option value="utf8mb4_croatian_mysql561_ci" title="Croatian (MySQL 5.6.1), case-insensitive">utf8mb4_croatian_mysql561_ci</option>
			<option value="utf8mb4_czech_ci" title="Czech (UCA 4.0.0), case-insensitive">utf8mb4_czech_ci</option>
			<option value="utf8mb4_danish_ci" title="Danish (UCA 4.0.0), case-insensitive">utf8mb4_danish_ci</option>
			<option value="utf8mb4_esperanto_ci" title="Esperanto (UCA 4.0.0), case-insensitive">utf8mb4_esperanto_ci</option>
			<option value="utf8mb4_estonian_ci" title="Estonian (UCA 4.0.0), case-insensitive">utf8mb4_estonian_ci</option>
			<option value="utf8mb4_general_ci" title="Unicode (UCA 4.0.0), case-insensitive">utf8mb4_general_ci</option>
			<option value="utf8mb4_german2_ci" title="German (phone book order) (UCA 4.0.0), case-insensitive">utf8mb4_german2_ci</option>
			<option value="utf8mb4_hungarian_ci" title="Hungarian (UCA 4.0.0), case-insensitive">utf8mb4_hungarian_ci</option>
			<option value="utf8mb4_icelandic_ci" title="Icelandic (UCA 4.0.0), case-insensitive">utf8mb4_icelandic_ci</option>
			<option value="utf8mb4_latvian_ci" title="Latvian (UCA 4.0.0), case-insensitive">utf8mb4_latvian_ci</option>
			<option value="utf8mb4_lithuanian_ci" title="Lithuanian (UCA 4.0.0), case-insensitive">utf8mb4_lithuanian_ci</option>
			<option value="utf8mb4_myanmar_ci" title="Burmese (UCA 4.0.0), case-insensitive">utf8mb4_myanmar_ci</option>
			<option value="utf8mb4_persian_ci" title="Persian (UCA 4.0.0), case-insensitive">utf8mb4_persian_ci</option>
			<option value="utf8mb4_polish_ci" title="Polish (UCA 4.0.0), case-insensitive">utf8mb4_polish_ci</option>
			<option value="utf8mb4_roman_ci" title="West European (UCA 4.0.0), case-insensitive">utf8mb4_roman_ci</option>
			<option value="utf8mb4_romanian_ci" title="Romanian (UCA 4.0.0), case-insensitive">utf8mb4_romanian_ci</option>
			<option value="utf8mb4_sinhala_ci" title="Sinhalese (UCA 4.0.0), case-insensitive">utf8mb4_sinhala_ci</option>
			<option value="utf8mb4_slovak_ci" title="Slovak (UCA 4.0.0), case-insensitive">utf8mb4_slovak_ci</option>
			<option value="utf8mb4_slovenian_ci" title="Slovenian (UCA 4.0.0), case-insensitive">utf8mb4_slovenian_ci</option>
			<option value="utf8mb4_spanish2_ci" title="Spanish (traditional) (UCA 4.0.0), case-insensitive">utf8mb4_spanish2_ci</option>
			<option value="utf8mb4_spanish_ci" title="Spanish (modern) (UCA 4.0.0), case-insensitive">utf8mb4_spanish_ci</option>
			<option value="utf8mb4_swedish_ci" title="Swedish (UCA 4.0.0), case-insensitive">utf8mb4_swedish_ci</option>
			<option value="utf8mb4_thai_520_w2" title="Thai (UCA 5.2.0), multi-level">utf8mb4_thai_520_w2</option>
			<option value="utf8mb4_turkish_ci" title="Turkish (UCA 4.0.0), case-insensitive">utf8mb4_turkish_ci</option>
			<option value="utf8mb4_unicode_520_ci" title="Unicode (UCA 5.2.0), case-insensitive">utf8mb4_unicode_520_ci</option>
			<option value="utf8mb4_unicode_ci" title="Unicode (UCA 4.0.0), case-insensitive">utf8mb4_unicode_ci</option>
			<option value="utf8mb4_vietnamese_ci" title="Vietnamese (UCA 4.0.0), case-insensitive">utf8mb4_vietnamese_ci</option>
		</optgroup>
	</select>
</template>
<script>

	export default {
		props: {
			table: Object
		},
		methods: {
			setOptionGroup: function(table, event){
				const index = event.target.selectedIndex;
				const selectedOption = event.target.options[index];
		        const selectedOptgroup = selectedOption.parentElement;
		        const selectedCategory = selectedOptgroup.getAttribute('label');
				table.charset = selectedCategory;
			}
		}
    }
</script>