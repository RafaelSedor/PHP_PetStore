<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use App\Models\Pet;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class PetsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pets = Pet::findByUserId($user->id);

        $this->render('pets/index', ['pets' => $pets, 'user' => $user]);
    }


    public function create(Request $request)
    {
        $data = $request->getAllParams();

        $pet = new Pet();
        $pet->name = $data['name'];
        $pet->species = $data['species'];
        $pet->age = $data['age'];
        $pet->user_id = Auth::user()->id;

        if ($pet->save()) {
            FlashMessage::success('Animal criado com sucesso.');
            $this->redirectTo('/user/' . Auth::user()->id . '/pets');
        } else {
            FlashMessage::danger('Erro ao criar o animal.');
            $this->redirectBack();
        }
    }


    public function store(Request $request)
    {
        $data = $request->getAllParams();
        $user = Auth::user();

        if (empty($data['name']) || empty($data['species']) || empty($data['age'])) {
            FlashMessage::danger('Todos os campos são obrigatórios.');
            $this->redirectBack();
            return;
        }

        $pet = new Pet();
        $pet->name = $data['name'];
        $pet->species = $data['species'];
        $pet->age = (int) $data['age'];
        $pet->user_id = $user->id;

        if ($pet->save()) {
            FlashMessage::success('Animal registrado com sucesso.');
            $this->redirectTo('/user/' . $user->id . '/pets');
        } else {
            FlashMessage::danger('Erro ao registrar o animal.');
            $this->redirectBack();
        }
    }

    public function edit(Request $request)
    {
        $id = $request->getParam('id');
        $pet = Pet::findById($id);
        $user = Auth::user();

        if (!$pet || $pet->user_id !== $user->id) {
            FlashMessage::danger('Animal não encontrado ou sem permissão.');
            $this->redirectTo('/user/' . $user->id . '/pets');
        }

        $this->render('pets/edit', ['pet' => $pet]);
    }

    public function update(Request $request)
    {
        $data = $request->getAllParams();
        $pet = Pet::findById($data['id']);

        if (!$pet || $pet->user_id !== Auth::user()->id) {
            FlashMessage::danger('Você não tem permissão para editar este animal.');
            $this->redirectTo('/user/' . Auth::user()->id . '/pets');
        }

        $pet->name = $data['name'];
        $pet->species = $data['species'];
        $pet->age = $data['age'];

        if ($pet->save()) {
            FlashMessage::success('Animal atualizado com sucesso.');
            $this->redirectTo('/user/' . Auth::user()->id . '/pets');
        } else {
            FlashMessage::danger('Erro ao atualizar o animal.');
            $this->redirectBack();
        }
    }


    public function delete(Request $request)
    {
        $data = $request->getAllParams();
        $pet = Pet::findById($data['id']);

        if (!$pet || $pet->user_id !== Auth::user()->id) {
            FlashMessage::danger('Você não tem permissão para excluir este animal.');
            $this->redirectTo('/user/' . Auth::user()->id . '/pets');
        }

        if ($pet->delete()) {
            FlashMessage::success('Animal excluído com sucesso.');
            $this->redirectTo('/user/' . Auth::user()->id . '/pets');
        } else {
            FlashMessage::danger('Erro ao excluir o animal.');
            $this->redirectBack();
        }
    }
}
