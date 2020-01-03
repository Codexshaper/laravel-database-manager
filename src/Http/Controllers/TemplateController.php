<?php

namespace CodexShaper\DBM\Http\Controllers;

use DBM;
use Illuminate\Http\Request;

class TemplateController extends Controller
{

    public function updateTemplates($templates)
    {
        if (is_array($templates) && count($templates) > 0) {

            foreach ($templates as $field) {

                if ($template = DBM::Template()->where('old_name', $field['oldName'])->first()) {

                    $template->name           = $field['name'];
                    $template->old_name       = $field['name'];
                    $template->type           = $field['type']['name'];
                    $template->length         = $field['length'];
                    $template->index          = $field['index'];
                    $template->default        = $field['default'];
                    $template->notnull        = $field['notnull'];
                    $template->unsigned       = $field['unsigned'];
                    $template->auto_increment = $field['autoincrement'];

                    $template->update();
                }
            }
        }
    }

    public function save(Request $request)
    {
        $field = $request->template;
        try
        {
            if (DBM::Template()->where('name', $field['name'])->first()) {
                return response()->json([
                    'success' => false,
                    'errors'  => [" The template name must be unique. " . $field['name'] . " already exist."],
                ], 400);
            }

            $template                 = DBM::Template();
            $template->name           = $field['name'];
            $template->old_name       = $field['name'];
            $template->type           = $field['type']['name'];
            $template->length         = $field['length'];
            $template->index          = $field['index'];
            $template->default        = $field['default'];
            $template->notnull        = $field['notnull'];
            $template->unsigned       = $field['unsigned'];
            $template->auto_increment = $field['autoincrement'];

            if ($template->save()) {
                return response()->json(['success' => true, 'templates' => DBM::templates()]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors'  => [$e->getMessage()],
            ], 400);
        }
        return response()->json(['success' => true, 'template' => $request->all()]);
    }

    public function remove(Request $request)
    {
        if ($template = DBM::Template()->where('name', $request->name)->first()) {
            if ($template->delete()) {
                return response()->json(['success' => true, 'templates' => DBM::templates()]);
            }
        }
        return response()->json([
            'success' => false,
            'errors'  => ['The template '+$request->name . " not found"],
        ], 400);
    }
}
