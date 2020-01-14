(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/collations/Mysql.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/js/components/collations/Mysql.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {},
  methods: {
    setOptionGroup: function setOptionGroup(field, event) {
      var index = event.target.selectedIndex;
      var selectedOption = event.target.options[index];
      var selectedOptgroup = selectedOption.parentElement;
      var selectedCategory = selectedOptgroup.getAttribute('label');
      field.type.category = selectedCategory;

      if (this.notSupportIndexs.includes(field.type.name)) {
        this.field.index = "";
      }

      if (this.notSupportDefault.includes(field.type.name)) {
        this.field["default"] = null;
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/collations/Mysql.vue?vue&type=template&id=8fadb058&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/js/components/collations/Mysql.vue?vue&type=template&id=8fadb058& ***!
  \**************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm._m(0)
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "select",
      {
        staticClass: "autosubmit",
        attrs: {
          lang: "en",
          dir: "ltr",
          name: "collation_connection",
          id: "select_collation_connection"
        }
      },
      [
        _c("option", { attrs: { value: "" } }, [_vm._v("Collation")]),
        _vm._v(" "),
        _c("option", { attrs: { value: "" } }),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "armscii8", title: "ARMSCII-8 Armenian" } },
          [
            _c(
              "option",
              { attrs: { value: "armscii8_bin", title: "Armenian, binary" } },
              [_vm._v("armscii8_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "armscii8_general_ci",
                  title: "Armenian, case-insensitive"
                }
              },
              [_vm._v("armscii8_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "ascii", title: "US ASCII" } }, [
          _c(
            "option",
            { attrs: { value: "ascii_bin", title: "West European, binary" } },
            [_vm._v("ascii_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ascii_general_ci",
                title: "West European, case-insensitive"
              }
            },
            [_vm._v("ascii_general_ci")]
          )
        ]),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "big5", title: "Big5 Traditional Chinese" } },
          [
            _c(
              "option",
              {
                attrs: {
                  value: "big5_bin",
                  title: "Traditional Chinese, binary"
                }
              },
              [_vm._v("big5_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "big5_chinese_ci",
                  title: "Traditional Chinese, case-insensitive"
                }
              },
              [_vm._v("big5_chinese_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "binary", title: "Binary pseudo charset" } },
          [
            _c("option", { attrs: { value: "binary", title: "Binary" } }, [
              _vm._v("binary")
            ])
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "cp1250", title: "Windows Central European" } },
          [
            _c(
              "option",
              {
                attrs: {
                  value: "cp1250_bin",
                  title: "Central European, binary"
                }
              },
              [_vm._v("cp1250_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1250_croatian_ci",
                  title: "Croatian, case-insensitive"
                }
              },
              [_vm._v("cp1250_croatian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1250_czech_cs",
                  title: "Czech, case-sensitive"
                }
              },
              [_vm._v("cp1250_czech_cs")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1250_general_ci",
                  title: "Central European, case-insensitive"
                }
              },
              [_vm._v("cp1250_general_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1250_polish_ci",
                  title: "Polish, case-insensitive"
                }
              },
              [_vm._v("cp1250_polish_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "cp1251", title: "Windows Cyrillic" } },
          [
            _c(
              "option",
              { attrs: { value: "cp1251_bin", title: "Cyrillic, binary" } },
              [_vm._v("cp1251_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1251_bulgarian_ci",
                  title: "Bulgarian, case-insensitive"
                }
              },
              [_vm._v("cp1251_bulgarian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1251_general_ci",
                  title: "Cyrillic, case-insensitive"
                }
              },
              [_vm._v("cp1251_general_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1251_general_cs",
                  title: "Cyrillic, case-sensitive"
                }
              },
              [_vm._v("cp1251_general_cs")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1251_ukrainian_ci",
                  title: "Ukrainian, case-insensitive"
                }
              },
              [_vm._v("cp1251_ukrainian_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "cp1256", title: "Windows Arabic" } },
          [
            _c(
              "option",
              { attrs: { value: "cp1256_bin", title: "Arabic, binary" } },
              [_vm._v("cp1256_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1256_general_ci",
                  title: "Arabic, case-insensitive"
                }
              },
              [_vm._v("cp1256_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "cp1257", title: "Windows Baltic" } },
          [
            _c(
              "option",
              { attrs: { value: "cp1257_bin", title: "Baltic, binary" } },
              [_vm._v("cp1257_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1257_general_ci",
                  title: "Baltic, case-insensitive"
                }
              },
              [_vm._v("cp1257_general_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp1257_lithuanian_ci",
                  title: "Lithuanian, case-insensitive"
                }
              },
              [_vm._v("cp1257_lithuanian_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "cp850", title: "DOS West European" } },
          [
            _c(
              "option",
              { attrs: { value: "cp850_bin", title: "West European, binary" } },
              [_vm._v("cp850_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp850_general_ci",
                  title: "West European, case-insensitive"
                }
              },
              [_vm._v("cp850_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "cp852", title: "DOS Central European" } },
          [
            _c(
              "option",
              {
                attrs: { value: "cp852_bin", title: "Central European, binary" }
              },
              [_vm._v("cp852_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp852_general_ci",
                  title: "Central European, case-insensitive"
                }
              },
              [_vm._v("cp852_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "cp866", title: "DOS Russian" } }, [
          _c(
            "option",
            { attrs: { value: "cp866_bin", title: "Russian, binary" } },
            [_vm._v("cp866_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "cp866_general_ci",
                title: "Russian, case-insensitive"
              }
            },
            [_vm._v("cp866_general_ci")]
          )
        ]),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "cp932", title: "SJIS for Windows Japanese" } },
          [
            _c(
              "option",
              { attrs: { value: "cp932_bin", title: "Japanese, binary" } },
              [_vm._v("cp932_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "cp932_japanese_ci",
                  title: "Japanese, case-insensitive"
                }
              },
              [_vm._v("cp932_japanese_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "dec8", title: "DEC West European" } },
          [
            _c(
              "option",
              { attrs: { value: "dec8_bin", title: "West European, binary" } },
              [_vm._v("dec8_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "dec8_swedish_ci",
                  title: "Swedish, case-insensitive"
                }
              },
              [_vm._v("dec8_swedish_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "eucjpms", title: "UJIS for Windows Japanese" } },
          [
            _c(
              "option",
              { attrs: { value: "eucjpms_bin", title: "Japanese, binary" } },
              [_vm._v("eucjpms_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "eucjpms_japanese_ci",
                  title: "Japanese, case-insensitive"
                }
              },
              [_vm._v("eucjpms_japanese_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "euckr", title: "EUC-KR Korean" } }, [
          _c(
            "option",
            { attrs: { value: "euckr_bin", title: "Korean, binary" } },
            [_vm._v("euckr_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "euckr_korean_ci",
                title: "Korean, case-insensitive"
              }
            },
            [_vm._v("euckr_korean_ci")]
          )
        ]),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "gb2312", title: "GB2312 Simplified Chinese" } },
          [
            _c(
              "option",
              {
                attrs: {
                  value: "gb2312_bin",
                  title: "Simplified Chinese, binary"
                }
              },
              [_vm._v("gb2312_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "gb2312_chinese_ci",
                  title: "Simplified Chinese, case-insensitive"
                }
              },
              [_vm._v("gb2312_chinese_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "gbk", title: "GBK Simplified Chinese" } },
          [
            _c(
              "option",
              {
                attrs: { value: "gbk_bin", title: "Simplified Chinese, binary" }
              },
              [_vm._v("gbk_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "gbk_chinese_ci",
                  title: "Simplified Chinese, case-insensitive"
                }
              },
              [_vm._v("gbk_chinese_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "geostd8", title: "GEOSTD8 Georgian" } },
          [
            _c(
              "option",
              { attrs: { value: "geostd8_bin", title: "Georgian, binary" } },
              [_vm._v("geostd8_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "geostd8_general_ci",
                  title: "Georgian, case-insensitive"
                }
              },
              [_vm._v("geostd8_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "greek", title: "ISO 8859-7 Greek" } },
          [
            _c(
              "option",
              { attrs: { value: "greek_bin", title: "Greek, binary" } },
              [_vm._v("greek_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "greek_general_ci",
                  title: "Greek, case-insensitive"
                }
              },
              [_vm._v("greek_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "hebrew", title: "ISO 8859-8 Hebrew" } },
          [
            _c(
              "option",
              { attrs: { value: "hebrew_bin", title: "Hebrew, binary" } },
              [_vm._v("hebrew_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "hebrew_general_ci",
                  title: "Hebrew, case-insensitive"
                }
              },
              [_vm._v("hebrew_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "hp8", title: "HP West European" } }, [
          _c(
            "option",
            { attrs: { value: "hp8_bin", title: "West European, binary" } },
            [_vm._v("hp8_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "hp8_english_ci",
                title: "English, case-insensitive"
              }
            },
            [_vm._v("hp8_english_ci")]
          )
        ]),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "keybcs2", title: "DOS Kamenicky Czech-Slovak" } },
          [
            _c(
              "option",
              {
                attrs: { value: "keybcs2_bin", title: "Czech-Slovak, binary" }
              },
              [_vm._v("keybcs2_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "keybcs2_general_ci",
                  title: "Czech-Slovak, case-insensitive"
                }
              },
              [_vm._v("keybcs2_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "koi8r", title: "KOI8-R Relcom Russian" } },
          [
            _c(
              "option",
              { attrs: { value: "koi8r_bin", title: "Russian, binary" } },
              [_vm._v("koi8r_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "koi8r_general_ci",
                  title: "Russian, case-insensitive"
                }
              },
              [_vm._v("koi8r_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "koi8u", title: "KOI8-U Ukrainian" } },
          [
            _c(
              "option",
              { attrs: { value: "koi8u_bin", title: "Ukrainian, binary" } },
              [_vm._v("koi8u_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "koi8u_general_ci",
                  title: "Ukrainian, case-insensitive"
                }
              },
              [_vm._v("koi8u_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "latin1", title: "cp1252 West European" } },
          [
            _c(
              "option",
              {
                attrs: { value: "latin1_bin", title: "West European, binary" }
              },
              [_vm._v("latin1_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin1_danish_ci",
                  title: "Danish, case-insensitive"
                }
              },
              [_vm._v("latin1_danish_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin1_general_ci",
                  title: "West European, case-insensitive"
                }
              },
              [_vm._v("latin1_general_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin1_general_cs",
                  title: "West European, case-sensitive"
                }
              },
              [_vm._v("latin1_general_cs")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin1_german1_ci",
                  title: "German (dictionary order), case-insensitive"
                }
              },
              [_vm._v("latin1_german1_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin1_german2_ci",
                  title: "German (phone book order), case-insensitive"
                }
              },
              [_vm._v("latin1_german2_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin1_spanish_ci",
                  title: "Spanish (modern), case-insensitive"
                }
              },
              [_vm._v("latin1_spanish_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin1_swedish_ci",
                  title: "Swedish, case-insensitive"
                }
              },
              [_vm._v("latin1_swedish_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "latin2", title: "ISO 8859-2 Central European" } },
          [
            _c(
              "option",
              {
                attrs: {
                  value: "latin2_bin",
                  title: "Central European, binary"
                }
              },
              [_vm._v("latin2_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin2_croatian_ci",
                  title: "Croatian, case-insensitive"
                }
              },
              [_vm._v("latin2_croatian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin2_czech_cs",
                  title: "Czech, case-sensitive"
                }
              },
              [_vm._v("latin2_czech_cs")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin2_general_ci",
                  title: "Central European, case-insensitive"
                }
              },
              [_vm._v("latin2_general_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin2_hungarian_ci",
                  title: "Hungarian, case-insensitive"
                }
              },
              [_vm._v("latin2_hungarian_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "latin5", title: "ISO 8859-9 Turkish" } },
          [
            _c(
              "option",
              { attrs: { value: "latin5_bin", title: "Turkish, binary" } },
              [_vm._v("latin5_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin5_turkish_ci",
                  title: "Turkish, case-insensitive"
                }
              },
              [_vm._v("latin5_turkish_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "latin7", title: "ISO 8859-13 Baltic" } },
          [
            _c(
              "option",
              { attrs: { value: "latin7_bin", title: "Baltic, binary" } },
              [_vm._v("latin7_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin7_estonian_cs",
                  title: "Estonian, case-sensitive"
                }
              },
              [_vm._v("latin7_estonian_cs")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin7_general_ci",
                  title: "Baltic, case-insensitive"
                }
              },
              [_vm._v("latin7_general_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "latin7_general_cs",
                  title: "Baltic, case-sensitive"
                }
              },
              [_vm._v("latin7_general_cs")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "macce", title: "Mac Central European" } },
          [
            _c(
              "option",
              {
                attrs: { value: "macce_bin", title: "Central European, binary" }
              },
              [_vm._v("macce_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "macce_general_ci",
                  title: "Central European, case-insensitive"
                }
              },
              [_vm._v("macce_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "macroman", title: "Mac West European" } },
          [
            _c(
              "option",
              {
                attrs: { value: "macroman_bin", title: "West European, binary" }
              },
              [_vm._v("macroman_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "macroman_general_ci",
                  title: "West European, case-insensitive"
                }
              },
              [_vm._v("macroman_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "sjis", title: "Shift-JIS Japanese" } },
          [
            _c(
              "option",
              { attrs: { value: "sjis_bin", title: "Japanese, binary" } },
              [_vm._v("sjis_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "sjis_japanese_ci",
                  title: "Japanese, case-insensitive"
                }
              },
              [_vm._v("sjis_japanese_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "swe7", title: "7bit Swedish" } }, [
          _c(
            "option",
            { attrs: { value: "swe7_bin", title: "Swedish, binary" } },
            [_vm._v("swe7_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "swe7_swedish_ci",
                title: "Swedish, case-insensitive"
              }
            },
            [_vm._v("swe7_swedish_ci")]
          )
        ]),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "tis620", title: "TIS620 Thai" } }, [
          _c(
            "option",
            { attrs: { value: "tis620_bin", title: "Thai, binary" } },
            [_vm._v("tis620_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "tis620_thai_ci",
                title: "Thai, case-insensitive"
              }
            },
            [_vm._v("tis620_thai_ci")]
          )
        ]),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "ucs2", title: "UCS-2 Unicode" } }, [
          _c(
            "option",
            { attrs: { value: "ucs2_bin", title: "Unicode, binary" } },
            [_vm._v("ucs2_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_croatian_ci",
                title: "Croatian, case-insensitive"
              }
            },
            [_vm._v("ucs2_croatian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_croatian_mysql561_ci",
                title: "Croatian (MySQL 5.6.1), case-insensitive"
              }
            },
            [_vm._v("ucs2_croatian_mysql561_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_czech_ci",
                title: "Czech, case-insensitive"
              }
            },
            [_vm._v("ucs2_czech_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_danish_ci",
                title: "Danish, case-insensitive"
              }
            },
            [_vm._v("ucs2_danish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_esperanto_ci",
                title: "Esperanto, case-insensitive"
              }
            },
            [_vm._v("ucs2_esperanto_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_estonian_ci",
                title: "Estonian, case-insensitive"
              }
            },
            [_vm._v("ucs2_estonian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_general_ci",
                title: "Unicode, case-insensitive"
              }
            },
            [_vm._v("ucs2_general_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_general_mysql500_ci",
                title: "Unicode (MySQL 5.0.0), case-insensitive"
              }
            },
            [_vm._v("ucs2_general_mysql500_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_german2_ci",
                title: "German (phone book order), case-insensitive"
              }
            },
            [_vm._v("ucs2_german2_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_hungarian_ci",
                title: "Hungarian, case-insensitive"
              }
            },
            [_vm._v("ucs2_hungarian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_icelandic_ci",
                title: "Icelandic, case-insensitive"
              }
            },
            [_vm._v("ucs2_icelandic_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_latvian_ci",
                title: "Latvian, case-insensitive"
              }
            },
            [_vm._v("ucs2_latvian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_lithuanian_ci",
                title: "Lithuanian, case-insensitive"
              }
            },
            [_vm._v("ucs2_lithuanian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_myanmar_ci",
                title: "Burmese, case-insensitive"
              }
            },
            [_vm._v("ucs2_myanmar_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_persian_ci",
                title: "Persian, case-insensitive"
              }
            },
            [_vm._v("ucs2_persian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_polish_ci",
                title: "Polish, case-insensitive"
              }
            },
            [_vm._v("ucs2_polish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_roman_ci",
                title: "West European, case-insensitive"
              }
            },
            [_vm._v("ucs2_roman_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_romanian_ci",
                title: "Romanian, case-insensitive"
              }
            },
            [_vm._v("ucs2_romanian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_sinhala_ci",
                title: "Sinhalese, case-insensitive"
              }
            },
            [_vm._v("ucs2_sinhala_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_slovak_ci",
                title: "Slovak, case-insensitive"
              }
            },
            [_vm._v("ucs2_slovak_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_slovenian_ci",
                title: "Slovenian, case-insensitive"
              }
            },
            [_vm._v("ucs2_slovenian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_spanish2_ci",
                title: "Spanish (traditional), case-insensitive"
              }
            },
            [_vm._v("ucs2_spanish2_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_spanish_ci",
                title: "Spanish (modern), case-insensitive"
              }
            },
            [_vm._v("ucs2_spanish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_swedish_ci",
                title: "Swedish, case-insensitive"
              }
            },
            [_vm._v("ucs2_swedish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_thai_520_w2",
                title: "Thai (UCA 5.2.0), multi-level"
              }
            },
            [_vm._v("ucs2_thai_520_w2")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_turkish_ci",
                title: "Turkish, case-insensitive"
              }
            },
            [_vm._v("ucs2_turkish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_unicode_520_ci",
                title: "Unicode (UCA 5.2.0), case-insensitive"
              }
            },
            [_vm._v("ucs2_unicode_520_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_unicode_ci",
                title: "Unicode, case-insensitive"
              }
            },
            [_vm._v("ucs2_unicode_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ucs2_vietnamese_ci",
                title: "Vietnamese, case-insensitive"
              }
            },
            [_vm._v("ucs2_vietnamese_ci")]
          )
        ]),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "ujis", title: "EUC-JP Japanese" } }, [
          _c(
            "option",
            { attrs: { value: "ujis_bin", title: "Japanese, binary" } },
            [_vm._v("ujis_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "ujis_japanese_ci",
                title: "Japanese, case-insensitive"
              }
            },
            [_vm._v("ujis_japanese_ci")]
          )
        ]),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "utf16", title: "UTF-16 Unicode" } }, [
          _c(
            "option",
            { attrs: { value: "utf16_bin", title: "Unicode, binary" } },
            [_vm._v("utf16_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_croatian_ci",
                title: "Croatian, case-insensitive"
              }
            },
            [_vm._v("utf16_croatian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_croatian_mysql561_ci",
                title: "Croatian (MySQL 5.6.1), case-insensitive"
              }
            },
            [_vm._v("utf16_croatian_mysql561_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_czech_ci",
                title: "Czech, case-insensitive"
              }
            },
            [_vm._v("utf16_czech_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_danish_ci",
                title: "Danish, case-insensitive"
              }
            },
            [_vm._v("utf16_danish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_esperanto_ci",
                title: "Esperanto, case-insensitive"
              }
            },
            [_vm._v("utf16_esperanto_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_estonian_ci",
                title: "Estonian, case-insensitive"
              }
            },
            [_vm._v("utf16_estonian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_general_ci",
                title: "Unicode, case-insensitive"
              }
            },
            [_vm._v("utf16_general_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_german2_ci",
                title: "German (phone book order), case-insensitive"
              }
            },
            [_vm._v("utf16_german2_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_hungarian_ci",
                title: "Hungarian, case-insensitive"
              }
            },
            [_vm._v("utf16_hungarian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_icelandic_ci",
                title: "Icelandic, case-insensitive"
              }
            },
            [_vm._v("utf16_icelandic_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_latvian_ci",
                title: "Latvian, case-insensitive"
              }
            },
            [_vm._v("utf16_latvian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_lithuanian_ci",
                title: "Lithuanian, case-insensitive"
              }
            },
            [_vm._v("utf16_lithuanian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_myanmar_ci",
                title: "Burmese, case-insensitive"
              }
            },
            [_vm._v("utf16_myanmar_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_persian_ci",
                title: "Persian, case-insensitive"
              }
            },
            [_vm._v("utf16_persian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_polish_ci",
                title: "Polish, case-insensitive"
              }
            },
            [_vm._v("utf16_polish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_roman_ci",
                title: "West European, case-insensitive"
              }
            },
            [_vm._v("utf16_roman_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_romanian_ci",
                title: "Romanian, case-insensitive"
              }
            },
            [_vm._v("utf16_romanian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_sinhala_ci",
                title: "Sinhalese, case-insensitive"
              }
            },
            [_vm._v("utf16_sinhala_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_slovak_ci",
                title: "Slovak, case-insensitive"
              }
            },
            [_vm._v("utf16_slovak_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_slovenian_ci",
                title: "Slovenian, case-insensitive"
              }
            },
            [_vm._v("utf16_slovenian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_spanish2_ci",
                title: "Spanish (traditional), case-insensitive"
              }
            },
            [_vm._v("utf16_spanish2_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_spanish_ci",
                title: "Spanish (modern), case-insensitive"
              }
            },
            [_vm._v("utf16_spanish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_swedish_ci",
                title: "Swedish, case-insensitive"
              }
            },
            [_vm._v("utf16_swedish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_thai_520_w2",
                title: "Thai (UCA 5.2.0), multi-level"
              }
            },
            [_vm._v("utf16_thai_520_w2")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_turkish_ci",
                title: "Turkish, case-insensitive"
              }
            },
            [_vm._v("utf16_turkish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_unicode_520_ci",
                title: "Unicode (UCA 5.2.0), case-insensitive"
              }
            },
            [_vm._v("utf16_unicode_520_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_unicode_ci",
                title: "Unicode, case-insensitive"
              }
            },
            [_vm._v("utf16_unicode_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf16_vietnamese_ci",
                title: "Vietnamese, case-insensitive"
              }
            },
            [_vm._v("utf16_vietnamese_ci")]
          )
        ]),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "utf16le", title: "UTF-16LE Unicode" } },
          [
            _c(
              "option",
              { attrs: { value: "utf16le_bin", title: "Unicode, binary" } },
              [_vm._v("utf16le_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf16le_general_ci",
                  title: "Unicode, case-insensitive"
                }
              },
              [_vm._v("utf16le_general_ci")]
            )
          ]
        ),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "utf32", title: "UTF-32 Unicode" } }, [
          _c(
            "option",
            { attrs: { value: "utf32_bin", title: "Unicode, binary" } },
            [_vm._v("utf32_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_croatian_ci",
                title: "Croatian, case-insensitive"
              }
            },
            [_vm._v("utf32_croatian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_croatian_mysql561_ci",
                title: "Croatian (MySQL 5.6.1), case-insensitive"
              }
            },
            [_vm._v("utf32_croatian_mysql561_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_czech_ci",
                title: "Czech, case-insensitive"
              }
            },
            [_vm._v("utf32_czech_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_danish_ci",
                title: "Danish, case-insensitive"
              }
            },
            [_vm._v("utf32_danish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_esperanto_ci",
                title: "Esperanto, case-insensitive"
              }
            },
            [_vm._v("utf32_esperanto_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_estonian_ci",
                title: "Estonian, case-insensitive"
              }
            },
            [_vm._v("utf32_estonian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_general_ci",
                title: "Unicode, case-insensitive"
              }
            },
            [_vm._v("utf32_general_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_german2_ci",
                title: "German (phone book order), case-insensitive"
              }
            },
            [_vm._v("utf32_german2_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_hungarian_ci",
                title: "Hungarian, case-insensitive"
              }
            },
            [_vm._v("utf32_hungarian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_icelandic_ci",
                title: "Icelandic, case-insensitive"
              }
            },
            [_vm._v("utf32_icelandic_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_latvian_ci",
                title: "Latvian, case-insensitive"
              }
            },
            [_vm._v("utf32_latvian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_lithuanian_ci",
                title: "Lithuanian, case-insensitive"
              }
            },
            [_vm._v("utf32_lithuanian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_myanmar_ci",
                title: "Burmese, case-insensitive"
              }
            },
            [_vm._v("utf32_myanmar_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_persian_ci",
                title: "Persian, case-insensitive"
              }
            },
            [_vm._v("utf32_persian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_polish_ci",
                title: "Polish, case-insensitive"
              }
            },
            [_vm._v("utf32_polish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_roman_ci",
                title: "West European, case-insensitive"
              }
            },
            [_vm._v("utf32_roman_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_romanian_ci",
                title: "Romanian, case-insensitive"
              }
            },
            [_vm._v("utf32_romanian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_sinhala_ci",
                title: "Sinhalese, case-insensitive"
              }
            },
            [_vm._v("utf32_sinhala_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_slovak_ci",
                title: "Slovak, case-insensitive"
              }
            },
            [_vm._v("utf32_slovak_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_slovenian_ci",
                title: "Slovenian, case-insensitive"
              }
            },
            [_vm._v("utf32_slovenian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_spanish2_ci",
                title: "Spanish (traditional), case-insensitive"
              }
            },
            [_vm._v("utf32_spanish2_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_spanish_ci",
                title: "Spanish (modern), case-insensitive"
              }
            },
            [_vm._v("utf32_spanish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_swedish_ci",
                title: "Swedish, case-insensitive"
              }
            },
            [_vm._v("utf32_swedish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_thai_520_w2",
                title: "Thai (UCA 5.2.0), multi-level"
              }
            },
            [_vm._v("utf32_thai_520_w2")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_turkish_ci",
                title: "Turkish, case-insensitive"
              }
            },
            [_vm._v("utf32_turkish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_unicode_520_ci",
                title: "Unicode (UCA 5.2.0), case-insensitive"
              }
            },
            [_vm._v("utf32_unicode_520_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_unicode_ci",
                title: "Unicode, case-insensitive"
              }
            },
            [_vm._v("utf32_unicode_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf32_vietnamese_ci",
                title: "Vietnamese, case-insensitive"
              }
            },
            [_vm._v("utf32_vietnamese_ci")]
          )
        ]),
        _vm._v(" "),
        _c("optgroup", { attrs: { label: "utf8", title: "UTF-8 Unicode" } }, [
          _c(
            "option",
            { attrs: { value: "utf8_bin", title: "Unicode, binary" } },
            [_vm._v("utf8_bin")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_croatian_ci",
                title: "Croatian, case-insensitive"
              }
            },
            [_vm._v("utf8_croatian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_croatian_mysql561_ci",
                title: "Croatian (MySQL 5.6.1), case-insensitive"
              }
            },
            [_vm._v("utf8_croatian_mysql561_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_czech_ci",
                title: "Czech, case-insensitive"
              }
            },
            [_vm._v("utf8_czech_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_danish_ci",
                title: "Danish, case-insensitive"
              }
            },
            [_vm._v("utf8_danish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_esperanto_ci",
                title: "Esperanto, case-insensitive"
              }
            },
            [_vm._v("utf8_esperanto_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_estonian_ci",
                title: "Estonian, case-insensitive"
              }
            },
            [_vm._v("utf8_estonian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_general_ci",
                title: "Unicode, case-insensitive"
              }
            },
            [_vm._v("utf8_general_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_general_mysql500_ci",
                title: "Unicode (MySQL 5.0.0), case-insensitive"
              }
            },
            [_vm._v("utf8_general_mysql500_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_german2_ci",
                title: "German (phone book order), case-insensitive"
              }
            },
            [_vm._v("utf8_german2_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_hungarian_ci",
                title: "Hungarian, case-insensitive"
              }
            },
            [_vm._v("utf8_hungarian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_icelandic_ci",
                title: "Icelandic, case-insensitive"
              }
            },
            [_vm._v("utf8_icelandic_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_latvian_ci",
                title: "Latvian, case-insensitive"
              }
            },
            [_vm._v("utf8_latvian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_lithuanian_ci",
                title: "Lithuanian, case-insensitive"
              }
            },
            [_vm._v("utf8_lithuanian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_myanmar_ci",
                title: "Burmese, case-insensitive"
              }
            },
            [_vm._v("utf8_myanmar_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_persian_ci",
                title: "Persian, case-insensitive"
              }
            },
            [_vm._v("utf8_persian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_polish_ci",
                title: "Polish, case-insensitive"
              }
            },
            [_vm._v("utf8_polish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_roman_ci",
                title: "West European, case-insensitive"
              }
            },
            [_vm._v("utf8_roman_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_romanian_ci",
                title: "Romanian, case-insensitive"
              }
            },
            [_vm._v("utf8_romanian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_sinhala_ci",
                title: "Sinhalese, case-insensitive"
              }
            },
            [_vm._v("utf8_sinhala_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_slovak_ci",
                title: "Slovak, case-insensitive"
              }
            },
            [_vm._v("utf8_slovak_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_slovenian_ci",
                title: "Slovenian, case-insensitive"
              }
            },
            [_vm._v("utf8_slovenian_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_spanish2_ci",
                title: "Spanish (traditional), case-insensitive"
              }
            },
            [_vm._v("utf8_spanish2_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_spanish_ci",
                title: "Spanish (modern), case-insensitive"
              }
            },
            [_vm._v("utf8_spanish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_swedish_ci",
                title: "Swedish, case-insensitive"
              }
            },
            [_vm._v("utf8_swedish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_thai_520_w2",
                title: "Thai (UCA 5.2.0), multi-level"
              }
            },
            [_vm._v("utf8_thai_520_w2")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_turkish_ci",
                title: "Turkish, case-insensitive"
              }
            },
            [_vm._v("utf8_turkish_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_unicode_520_ci",
                title: "Unicode (UCA 5.2.0), case-insensitive"
              }
            },
            [_vm._v("utf8_unicode_520_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_unicode_ci",
                title: "Unicode, case-insensitive"
              }
            },
            [_vm._v("utf8_unicode_ci")]
          ),
          _vm._v(" "),
          _c(
            "option",
            {
              attrs: {
                value: "utf8_vietnamese_ci",
                title: "Vietnamese, case-insensitive"
              }
            },
            [_vm._v("utf8_vietnamese_ci")]
          )
        ]),
        _vm._v(" "),
        _c(
          "optgroup",
          { attrs: { label: "utf8mb4", title: "UTF-8 Unicode" } },
          [
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_bin",
                  title: "Unicode (UCA 4.0.0), binary"
                }
              },
              [_vm._v("utf8mb4_bin")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_croatian_ci",
                  title: "Croatian (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_croatian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_croatian_mysql561_ci",
                  title: "Croatian (MySQL 5.6.1), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_croatian_mysql561_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_czech_ci",
                  title: "Czech (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_czech_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_danish_ci",
                  title: "Danish (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_danish_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_esperanto_ci",
                  title: "Esperanto (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_esperanto_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_estonian_ci",
                  title: "Estonian (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_estonian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_general_ci",
                  title: "Unicode (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_general_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_german2_ci",
                  title:
                    "German (phone book order) (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_german2_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_hungarian_ci",
                  title: "Hungarian (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_hungarian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_icelandic_ci",
                  title: "Icelandic (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_icelandic_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_latvian_ci",
                  title: "Latvian (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_latvian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_lithuanian_ci",
                  title: "Lithuanian (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_lithuanian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_myanmar_ci",
                  title: "Burmese (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_myanmar_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_persian_ci",
                  title: "Persian (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_persian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_polish_ci",
                  title: "Polish (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_polish_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_roman_ci",
                  title: "West European (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_roman_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_romanian_ci",
                  title: "Romanian (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_romanian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_sinhala_ci",
                  title: "Sinhalese (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_sinhala_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_slovak_ci",
                  title: "Slovak (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_slovak_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_slovenian_ci",
                  title: "Slovenian (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_slovenian_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_spanish2_ci",
                  title: "Spanish (traditional) (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_spanish2_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_spanish_ci",
                  title: "Spanish (modern) (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_spanish_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_swedish_ci",
                  title: "Swedish (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_swedish_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_thai_520_w2",
                  title: "Thai (UCA 5.2.0), multi-level"
                }
              },
              [_vm._v("utf8mb4_thai_520_w2")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_turkish_ci",
                  title: "Turkish (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_turkish_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_unicode_520_ci",
                  title: "Unicode (UCA 5.2.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_unicode_520_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_unicode_ci",
                  title: "Unicode (UCA 4.0.0), case-insensitive",
                  selected: "selected"
                }
              },
              [_vm._v("utf8mb4_unicode_ci")]
            ),
            _vm._v(" "),
            _c(
              "option",
              {
                attrs: {
                  value: "utf8mb4_vietnamese_ci",
                  title: "Vietnamese (UCA 4.0.0), case-insensitive"
                }
              },
              [_vm._v("utf8mb4_vietnamese_ci")]
            )
          ]
        )
      ]
    )
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/assets/js/components/collations/Mysql.vue":
/*!*************************************************************!*\
  !*** ./resources/assets/js/components/collations/Mysql.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Mysql_vue_vue_type_template_id_8fadb058___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Mysql.vue?vue&type=template&id=8fadb058& */ "./resources/assets/js/components/collations/Mysql.vue?vue&type=template&id=8fadb058&");
/* harmony import */ var _Mysql_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Mysql.vue?vue&type=script&lang=js& */ "./resources/assets/js/components/collations/Mysql.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Mysql_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Mysql_vue_vue_type_template_id_8fadb058___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Mysql_vue_vue_type_template_id_8fadb058___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/js/components/collations/Mysql.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/js/components/collations/Mysql.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/assets/js/components/collations/Mysql.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Mysql_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Mysql.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/collations/Mysql.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Mysql_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/js/components/collations/Mysql.vue?vue&type=template&id=8fadb058&":
/*!********************************************************************************************!*\
  !*** ./resources/assets/js/components/collations/Mysql.vue?vue&type=template&id=8fadb058& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Mysql_vue_vue_type_template_id_8fadb058___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Mysql.vue?vue&type=template&id=8fadb058& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/js/components/collations/Mysql.vue?vue&type=template&id=8fadb058&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Mysql_vue_vue_type_template_id_8fadb058___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Mysql_vue_vue_type_template_id_8fadb058___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);