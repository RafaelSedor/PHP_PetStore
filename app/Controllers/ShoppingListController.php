<?php

namespace App\Controllers;

use App\Models\ShoppingList;
use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class ShoppingListController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->getParam('status');
        $userName = $request->getParam('user_name');

        if ($status || $userName) {
            $shoppingLists = ShoppingList::filter($status, $userName);
        } else {
            $shoppingLists = ShoppingList::findAll();
        }

        foreach ($shoppingLists as $list) {
            $list->items = ShoppingList::findItemsByListId($list->id);
        }

        $this->render('shopping_lists/index', [
            'shoppingLists' => $shoppingLists,
            'filters' => [
                'status' => $status,
                'user_name' => $userName,
            ],
        ]);
    }

    public function show(Request $request)
    {
        $userId = $request->getParam('user_id');
        $shoppingList = ShoppingList::findByUser($userId);

        if (!$shoppingList) {
            FlashMessage::danger('Nenhuma lista de compras encontrada para este usuário.');
            $this->redirectBack();
        }

        foreach ($shoppingList as $list) {
            $list->items = ShoppingList::findItemsByListId($list->id);
        }

        $user = User::findById($userId);

        $this->render('shopping_lists/show', [
            'shoppingList' => $shoppingList,
            'user' => $user,
        ]);
    }

    public function delete(Request $request)
    {
        $id = $request->getParam('id');
        $currentUser = Auth::user();

        $shoppingList = ShoppingList::findById($id);
        if (!$shoppingList) {
            FlashMessage::danger('Lista de compras não encontrada.');
            $this->redirectBack();
        }

        if ($currentUser->role !== 'admin' && $shoppingList->user_id !== $currentUser->id) {
            FlashMessage::danger('Você não tem permissão para excluir esta lista de compras.');
            $this->redirectBack();
        }

        if (ShoppingList::delete($id)) {
            FlashMessage::success('Lista de compras excluída com sucesso.');
        } else {
            FlashMessage::danger('Erro ao excluir a lista de compras.');
        }

        $this->redirectBack();
    }

    public function finalize(Request $request)
    {
        $id = $request->getParam('id');
        $shoppingList = ShoppingList::findById($id);

        if (!$shoppingList || $shoppingList->status !== 'open') {
            FlashMessage::danger('Não foi possível finalizar a lista.');
            $this->redirectBack();
        }

        $shoppingList->updateStatus('closed');
        FlashMessage::success('Lista finalizada com sucesso.');
        $this->redirectBack();
    }
}
