<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * Load backups.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('dbm::app');
    }

    /**
     * Get all backup files.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function backups(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('backup.browse')) !== true) {
                return $response;
            }

            $userPermissions = DBM::userPermissions();
            $files = $this->getPaginateFiles($request);
            $results = [];
            foreach ($files as $file) {
                $results[] = (object) [
                    'info' => pathinfo($file),
                    'lastModified' => date('F j, Y, g:i a', Storage::lastModified($file)),
                    'size' => Storage::size($file),
                ];
            }

            return response()->json([
                'success' => true,
                'files' => $results,
                'userPermissions' => $userPermissions,
                'pagination' => $files,
            ]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Get Pagination Data.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginateFiles(Request $request)
    {
        $driver = dbm_driver();
        $directory = 'backups'.DIRECTORY_SEPARATOR.$driver;
        $files = collect(Storage::allFiles($directory));

        $query = $request->q;
        if (! empty($query)) {
            $files = $files->filter(function ($file) use ($query) {
                $info = pathinfo($file);

                return false !== stristr($info['basename'], $query);
            });
        }
        $page = (int) $request->page ?: 1;
        $perPage = (int) $request->perPage;
        $slice = $files->slice(($page - 1) * $perPage, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator($slice, $files->count(), $perPage);
    }

    /**
     * Create new backup.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function backup(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('backup.create')) !== true) {
                return $response;
            }

            try {
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
                    'errors' => [$e->getMessage()],
                ], 200);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Restore from a specific backup.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('backup.restore')) !== true) {
                return $response;
            }

            try {
                Artisan::call('dbm:restore', [
                    '--path' => $request->path,
                ]);

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => true,
                    'errors' => [$e->getMessage()],
                ], 200);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Return specific backup file for download.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function download(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('backup.download')) !== true) {
                return $response;
            }

            try {
                $file = Storage::get($request->path);

                return response()->json(['success' => true, 'file' => $file]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => true,
                    'errors' => [$e->getMessage()],
                ], 200);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Remove a backup.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('backup.delete')) !== true) {
                return $response;
            }

            try {
                Storage::delete($request->path);

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => true,
                    'errors' => [$e->getMessage()],
                ], 200);
            }
        }

        return response()->json(['success' => false]);
    }
}
