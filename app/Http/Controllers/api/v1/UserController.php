<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
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
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
