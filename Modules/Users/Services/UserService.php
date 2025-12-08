<?php

namespace Modules\Users\Services;

use Modules\Users\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserService implements UserServiceInterface
{
    protected $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        $auth = auth()->user();

        if (!$auth->hasRole('admin')) {
            throw new \Exception("Səlahiyyət yoxdur", 403);
        }

        $data['password'] = Hash::make($data['password']);

        return DB::transaction(function () use ($data) {

            // create user
            $user = $this->repo->create($data);

            // assign role
            $role = $data['role'] ?? 'employee';
            $user->assignRole($role);

            // token
            $token = $user->createToken('API Token')->plainTextToken;

            return [
                "user"  => $user->load('roles'),
                "token" => $token
            ];
        });
    }

    public function update($id, array $data)
    {
        $auth = auth()->user();
        $user = User::findOrFail($id);

        if (!$auth->hasRole('admin') && !empty($data['role'])) {
            throw new \Exception("Səlahiyyət yoxdur", 403);
        }


        if (!empty($data['role'])) {
            $adminCount = User::role('admin')->count();

            if ($user->hasRole('admin') && $data['role'] !== 'admin' && $adminCount == 1) {
                throw new \Exception("Sistemdə ən az 1 admin olmalıdır.", 403);
            }
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return DB::transaction(function () use ($id, $user, $data) {

            // update user
            $updatedUser = $this->repo->update($id, $data);

            // sync roles
            if (!empty($data['role'])) {
                $updatedUser->syncRoles([$data['role']]);
            }

            return $updatedUser->load('roles');
        });
    }

    public function delete($id)
    {
        $auth = auth()->user();
        $user = User::findOrFail($id);

        if (!$auth->hasRole('admin')) {
            throw new \Exception("Səlahiyyət yoxdur", 403);
        }

        if ($user->hasRole('admin') && User::role('admin')->count() == 1) {
            throw new \Exception("Sistemdə ən az 1 admin olmalıdır.", 403);
        }

        return DB::transaction(function () use ($id) {
            return $this->repo->delete($id);
        });
    }
}
