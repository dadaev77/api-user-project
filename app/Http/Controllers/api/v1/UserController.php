<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Получить список всех пользователей с пагинацией, поиском и сортировкой
    public function index(Request $request)
    {
        // Получаем параметры поиска и сортировки из запроса
        $search = $request->input('search');  // Параметр для поиска по имени
        $sort = $request->input('sort', 'asc');  // Параметр для сортировки (по умолчанию по возрастанию)
        $perPage = $request->input('per_page', 10); // Количество элементов на странице (по умолчанию 10)

        // Строим запрос
        $usersQuery = User::query();

        // Если есть параметр поиска, фильтруем по имени
        if ($search) {
            $usersQuery->where('name', 'like', '%' . $search . '%');
        }

        // Сортируем по имени
        $usersQuery->orderBy('name', $sort);

        // Получаем пользователей с пагинацией
        $users = $usersQuery->paginate($perPage);

        // Возвращаем ресурс с пагинированными данными
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // Создание нового пользователя
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        return response()->json(new UserResource($user), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // Получить пользователея по id
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // Обновление данных пользователя
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
