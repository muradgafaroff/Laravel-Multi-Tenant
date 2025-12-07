<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\UserStoreRequest;
use Modules\Users\Http\Requests\UserUpdateRequest;
use Modules\Users\Services\UserServiceInterface;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }

    /** Get all users */
    public function index()
    {
        return response()->json($this->service->getAll());
    }

    /** Create new user */
    public function store(UserStoreRequest $request)
    {
        try {
            $data = $this->service->create($request->validated());
            return response()->json([
                "message" => "User uğurla yaradıldı.",
                "data"    => $data
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    /** Show user */
    public function show($id)
    {
        return response()->json($this->service->find($id));
    }

    /** Update user */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $data = $this->service->update($id, $request->validated());

            return response()->json([
                "message" => "User yeniləndi",
                "data"    => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    /** Delete user */
    public function destroy($id)
    {
        try {
            $this->service->delete($id);

            return response()->json(["message" => "User silindi"]);

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
