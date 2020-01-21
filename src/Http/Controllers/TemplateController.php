<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Update Templates.
     *
     * @return void
     */
    public function updateTemplates(Request $request)
    {
        if (is_array($request->templates) && count($request->templates) > 0) {
            foreach ($request->templates as $field) {
                if ($template = DBM::Template()->where('old_name', $field['oldName'])->first()) {
                    $template->name = $field['name'];
                    $template->old_name = $field['name'];
                    $template->type = $field['type']['name'];
                    $template->length = $field['length'];
                    $template->index = $field['index'];
                    $template->default = $field['default'];
                    $template->notnull = $field['notnull'];
                    $template->unsigned = $field['unsigned'];
                    $template->auto_increment = $field['autoincrement'];

                    $template->update();
                }
            }
        }
    }

    /**
     * Create a new template.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $field = $request->template;
        try {
            if (DBM::Template()->where('name', $field['name'])->first()) {
                return response()->json([
                    'success' => false,
                    'errors' => [' The template name must be unique. '.$field['name'].' already exist.'],
                ], 400);
            }

            $template = DBM::Template();
            $template->name = $field['name'];
            $template->old_name = $field['name'];
            $template->type = $field['type']['name'];
            $template->length = $field['length'];
            $template->index = $field['index'];
            $template->default = $field['default'];
            $template->notnull = $field['notnull'];
            $template->unsigned = $field['unsigned'];
            $template->auto_increment = $field['autoincrement'];

            if ($template->save()) {
                return response()->json(['success' => true, 'templates' => DBM::templates()]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [$e->getMessage()],
            ], 400);
        }

        return response()->json(['success' => true, 'template' => $request->all()]);
    }

    /**
     * Remove a template.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request)
    {
        if ($template = DBM::Template()->where('name', $request->name)->first()) {
            if ($template->delete()) {
                return response()->json(['success' => true, 'templates' => DBM::templates()]);
            }
        }

        return response()->json([
            'success' => false,
            'errors' => ['The template ' + $request->name.' not found'],
        ], 400);
    }
}
