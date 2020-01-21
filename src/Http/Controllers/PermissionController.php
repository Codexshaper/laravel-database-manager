<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Get all permissions.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('dbm::app');
    }

    /**
     * Get all users with permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('permission.browse')) !== true) {
                return $response;
            }

            try {
                $users = $this->getUsers($request);

                $privileges = DBM::Permission()->all();

                $permissions = new \StdClass;
                $permissions->database = DBM::Permission()->where('prefix', 'database')->get();
                $permissions->crud = DBM::Permission()->where('prefix', 'crud')->get();
                $permissions->relationship = DBM::Permission()->where('prefix', 'relationship')->get();
                $permissions->record = DBM::Permission()->where('prefix', 'record')->get();
                $permissions->backup = DBM::Permission()->where('prefix', 'backup')->get();
                $permissions->permission = DBM::Permission()->where('prefix', 'permission')->get();

                return response()->json([
                    'success' => true,
                    'privileges' => $privileges,
                    'permissions' => $permissions,
                    'pagination' => $users,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'errors' => [$e->getMessage()],
                ], 200);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * get Permission Users.
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function getUsers(Request $request)
    {
        $user_model = config('dbm.auth.user.model');
        $user_table = config('dbm.auth.user.table');
        $user_local_key = config('dbm.auth.user.local_key');
        $user_display_name = config('dbm.auth.user.display_name');

        $perPage = (int) $request->perPage;
        $query = $request->q;
        $users = DBM::model($user_model, $user_table)->paginate($perPage);

        if (! empty($query)) {
            $users = DBM::model($user_model, $user_table)
                ->where('name', 'LIKE', '%'.$query.'%')
                ->paginate($perPage);
        }

        $users->getCollection()->transform(function ($user) use ($user_display_name) {
            $user->permissions = DBM::Object()
                ->setManyToManyRelation(
                    $user,
                    DBM::Permission(),
                    'dbm_user_permissions',
                    'user_id',
                    'dbm_permission_id'
                )
                ->belongs_to_many;
            $user->display_name = $user_display_name;

            return $user;
        });

        return $users;
    }

    /**
     * Assign Permissions to User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignUserPermissions(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('permission.create')) !== true) {
                return $response;
            }

            $privileges = $request->privileges;
            $user = (object) $request->user;

            $this->getRelation($user)->attach($privileges);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Update User Permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncUserPermissions(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('permission.update')) !== true) {
                return $response;
            }

            $privileges = $request->privileges;
            $user = (object) $request->user;

            $this->getRelation($user)->sync($privileges);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Delete User Permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUserPermissions(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('permission.delete')) !== true) {
                return $response;
            }

            $user = json_decode($request->user);

            $this->getRelation($user)->detach();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Get User Relation.
     *
     * @param object $user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected function getRelation($user)
    {
        $user_model = config('dbm.auth.user.model');
        $user_table = config('dbm.auth.user.table');
        $user_local_key = config('dbm.auth.user.local_key');
        $user_display_name = config('dbm.auth.user.display_name');

        $localModel = DBM::model($user_model, $user_table)
            ->where($user_local_key, $user->{$user_local_key})
            ->first();

        return DBM::Object()
            ->setManyToManyRelation(
                $localModel,
                DBM::Permission(),
                'dbm_user_permissions',
                'user_id',
                'dbm_permission_id'
            )
            ->belongs_to_many();
    }
}
