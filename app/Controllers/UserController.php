<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\FlashMessage;
use Core\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $this->render('user/index', ['users' => $users]);
    }

    public function show(Request $request)
    {
        $id = $request->getParam('id');
        $user = User::findById($id);

        if (!$user) {
            FlashMessage::danger('Usuário não encontrado.');
            $this->redirectTo('/users');
        }

        $this->render('user/show', ['user' => $user]);
    }

    public function create()
    {
        $this->render('user/create');
    }

    public function store(Request $request)
    {
        $data = $request->getAllParams();

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->role = $data['role'];

        if ($user->save()) {
            FlashMessage::success('Usuário criado com sucesso.');
            $this->redirectTo('/users');
        } else {
            FlashMessage::danger('Erro ao criar o usuário.');
            $this->redirectBack();
        }
    }

    public function edit(Request $request)
    {
        $id = $request->getParam('id');
        $user = User::findById($id);

        if (!$user) {
            FlashMessage::danger('Usuário não encontrado.');
            $this->redirectTo('/users');
        }

        $this->render('user/edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $data = $request->getAllParams();
        $id = $data['id'];

        $user = User::findById($id);

        if (!$user) {
            FlashMessage::danger('Usuário não encontrado.');
            $this->redirectTo('/users');
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $user->role = $data['role'];

        if ($user->save()) {
            FlashMessage::success('Usuário atualizado com sucesso.');
            $this->redirectTo('/users');
        } else {
            FlashMessage::danger('Erro ao atualizar o usuário.');
            $this->redirectBack();
        }
    }

    public function delete(Request $request)
    {
        $id = $request->getParam('id');
        $user = User::findById($id);

        if (!$user) {
            FlashMessage::danger('Usuário não encontrado.');
            $this->redirectTo('/users');
        }

        if ($user->delete()) {
            FlashMessage::success('Usuário excluído com sucesso.');
        } else {
            FlashMessage::danger('Erro ao excluir o usuário.');
        }

        $this->redirectTo('/users');
    }
}
