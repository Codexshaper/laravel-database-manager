<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Database\Schema\Table;
use DBM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{

    public function index()
    {
        return view('dbm::app');
    }

    public function backups(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('backup.browse')) !== true) {
                return $response;
            }

            $userPermissions = DBM::userPermissions();
            $driver          = dbm_driver();
            $directory       = 'backups' . DIRECTORY_SEPARATOR . $driver;
            $files           = array_reverse(Storage::files($directory));
            $results         = [];
            foreach ($files as $file) {
                $results[] = (object) [
                    'info'         => pathinfo($file),
                    'lastModified' => date("F j, Y, g:i a", Storage::lastModified($file)),
                    'size'         => Storage::size($file),
                ];
            }

            return response()->json(['success' => true, 'files' => $results, 'userPermissions' => $userPermissions]);
        }

        return response()->json(['success' => false]);

    }

    public function backup(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('backup.create')) !== true) {
                return $response;
            }

            try
            {
                $table = null;

                if ($request->isTable) {
                    $table = $request->table;
                }

                Artisan::call('dbm:backup', [
                    '--table' => $table,
                ]);

                return response()->json(['success' => true]);

            } catch (\Exception $e) {

                return response()->json([
                    'success' => true,
                    'errors'  => [$e->getMessage()],
                ], 200);
            }
        }

        return response()->json(['success' => false]);
    }

    public function restore(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('backup.restore')) !== true) {
                return $response;
            }

            try
            {
                Artisan::call('dbm:restore', [
                    '--path' => $request->path,
                ]);
                return response()->json(['success' => true]);

            } catch (\Exception $e) {

                return response()->json([
                    'success' => true,
                    'errors'  => [$e->getMessage()],
                ], 200);
            }
        }

        return response()->json(['success' => false]);

    }

    public function download(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('backup.download')) !== true) {
                return $response;
            }

            try
            {
                $file = Storage::get($request->path);
                return response()->json(['success' => true, 'file' => $file]);

            } catch (\Exception $e) {

                return response()->json([
                    'success' => true,
                    'errors'  => [$e->getMessage()],
                ], 200);
            }
        }

        return response()->json(['success' => false]);
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('backup.delete')) !== true) {
                return $response;
            }

            try
            {
                Storage::delete($request->path);
                return response()->json(['success' => true]);

            } catch (\Exception $e) {

                return response()->json([
                    'success' => true,
                    'errors'  => [$e->getMessage()],
                ], 200);
            }
        }

        return response()->json(['success' => false]);
    }
}
