<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getPaginate($perPage)
    {
        return $this->user->latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return $this->user->find($id);
    }

    public function update($id, $request)
    {
        $user = $this->user->find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return $user;
    }

    public function save($request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return $user;
    }

    public function delete($id)
    {
        $user = $this->user->find($id);
        $user->delete();
    }
}
